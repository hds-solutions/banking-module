<?php

namespace HDSSolutions\Laravel\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Validator;

class DepositSlip extends A_DepositSlip {

    public function __construct(array $attributes = []) {
        // redirect attributes to parent
        parent::__construct((is_array($attributes) ? $attributes : []) + [
            // force transaction_type=Deposit
            'transaction_type'  => self::TRANSACTION_TYPE_Deposit,
        ]);
    }

    protected static function booted() {
        static::addGlobalScope('deposit_slip', fn(Builder $query) => $query->where('transaction_type', self::TRANSACTION_TYPE_Deposit));
        self::retrieved(function($model) {
            // append identity fields
            $model->appends = [ ...$model->appends,  ...[ 'checks_amount' ] ];
        });
    }

    public function setTransactionTypeAttribute(string $ignored):void {
        parent::setTransactionTypeAttribute(self::TRANSACTION_TYPE_Deposit);
    }

    public function movement() {
        return $this->belongsTo(BankAccountMovement::class, 'bank_account_movement_id');
    }

    public function checks() {
        return $this->belongsToMany(Check::class, 'deposit_slip_check')
            ->using(DepositSlipCheck::class)
            ->withTimestamps()
            ->as('depositSlipCheck');
    }

    public function getChecksAmountAttribute() {
        return $this->checks->sum('payment_amount');
    }

    protected function beforeSave(Validator $validator) {
        // execute A_DepositSlip validations
        parent::beforeSave($validator);

        // check if amount was set but no Cash is set
        if ($this->cash_amount > 0 && $this->cash_id === null)
            // reject with error
            return $validator->errors()->add('cash_id', __('banking::deposit_slip.beforeSave.no-cash-specified'));
    }

    public function prepareIt():?string {
        // validate that there is cash or checks
        if ($this->cash_amount == 0 && !$this->checks()->count())
            // reject document, no lines
            return $this->documentError('banking::deposit_slip.prepareIt.no-lines');

        // validate if cash_amount then cash must be open
        if ($this->cash_amount > 0) {
            // validate that cash is open
            if (!$this->cash->isOpen())
                // reject document, no open cash
                return $this->documentError('banking::deposit_slip.prepareIt.cash-not-open', [
                    'cash' => $this->cash->description ?? $this->cash->cashBook->name,
                ]);

            // validate cash currency
            if ($this->cash->currency_id !== $this->bankAccount->currency_id)
                // reject document, currency missmatch
                return $this->documentError('banking::deposit_slip.prepareIt.currency-missmatch', [
                    'bankAccount'   => $this->bankAccount->description ?? $this->bankAccount->account_number,
                    'cash'          => $this->cash->cashBook->name,
                ]);

            // check that cash has enough balance
            if ($this->cash->end_balance < $this->cash_amount)
                // reject document, no enough cash
                return $this->documentError('banking::deposit_slip.prepareIt.cash-no-balance', [
                    'cash'  => $this->cash->cashBook->name,
                ]);
        }

        // validate checks currency and deposited status
        foreach ($this->checks as $check) {
            // validate that check isn't expired, isn't deposited and isn't cashed
            foreach ([
                'is_expired'    => 'check-expired',
                'is_deposited'  => 'check-already-deposited',
                'is_cashed'     => 'check-already-cashed',
            ] as $status => $message)
                // validate that check passes statis
                if ($check->$status)
                    // reject document, invalid check statis
                    return $this->documentError('banking::deposit_slip.prepareIt.'.$message, [
                        'check'         => $check->document_number,
                        'bankAccount'   => $check->bankAccount?->description ?? $check->bankAccount?->account_number,
                        'cash'          => $check->cash?->description ?? $check->cash?->cashBook?->name,
                    ]);

            // valiate that check is from the same currency of BankAccount
            if ($check->currency_id !== $this->bankAccount->currency_id)
                // reject document, currency missmatch
                return $this->documentError('banking::deposit_slip.prepareIt.check-currency-missmatch', [
                    'check'         => $check->document_number,
                    'bankAccount'   => $this->bankAccount->description ?? $this->bankAccount->account_number,
                ]);
        }

        // return status InProgress
        return self::STATUS_InProgress;
    }

    public final function completeIt():?string {
        // if cash amount, substract from cash, add to bank_account
        if ($this->cash_amount > 0) {
            // create out movement on cash
            $out = $this->cash->lines()->create([
                'cash_type'     => CashLine::CASH_TYPE_BankDeposit,
                'currency_id'   => $this->cash->currency_id, // TODO: Fix validation to allow beforeSave() before Validation->validate()
                'description'   => __('banking::deposit_slip.completeIt.cash-line', [
                    'account_type'      => __(BankAccount::ACCOUNT_TYPES[$this->bankAccount->account_type]),
                    'account_number'    => $this->bankAccount->account_number,
                    'bankAccount'       => $this->bankAccount->description ?? $this->bankAccount->account_number,
                ]),
                // negated amount since we are extracting cash
                'amount'        => $this->cash_amount * -1,
            ]);
            // link movement with this deposit
            $out->referable()->associate( $this );
            // save movement
            if (!$out->save())
                // return document error
                return $this->documentError( $out->errors()->first() );

            // generate a bankAccount movement
            $movement = new BankAccountMovement([
                'transacted_at'     => $this->transacted_at,
                'bank_account_id'   => $this->bank_account_id,
                'movement_type'     => BankAccountMovement::MOVEMENT_TYPE_CashDeposit,
                'description'       => __('banking::deposit_slip.completeIt.cash-movement', [
                    'cash'              => $out->cash->description ?? $out->cash->cashBook->name,
                ]),
                'amount'            => $this->cash_amount,
                // cash is always confirmed
                'confirmed'     => true,
            ]);
            $movement->referable()->associate( $out );
            if (!$movement->save())
                // return document error
                return $this->documentError( $movement->errors()->first() );

            // set movement on DepositSlip
            $this->movement()->associate( $movement );
            if (!$this->save())
                // return document error
                return $this->documentError( $this->errors()->first() );
        }

        // mark checks as deposited
        foreach ($this->checks as $check) {
            // deposit check to bank account
            if (!$check->update([
                // mark check as deposited
                'is_deposited'      => true,
                // set bank account
                'bank_account_id'   => $this->bankAccount->id,
            ]))
                // return document error
                return $this->documentError( $check->errors()->first() );

            // generate a bankAccount movement
            $movement = new BankAccountMovement([
                'transacted_at'     => $this->transacted_at,
                'bank_account_id'   => $this->bank_account_id,
                'movement_type'     => BankAccountMovement::MOVEMENT_TYPE_CheckDeposit,
                'description'       => __('banking::deposit_slip.completeIt.check-movement', [
                    'check'             => $check->document_number,
                ]),
                'amount'            => $check->payment_amount,
            ]);
            $movement->referable()->associate( $check );
            if (!$movement->save())
                // return document error
                return $this->documentError( $movement->errors()->first() );

            // set movement on DepositSlipCheck
            if (!$this->checks()->updateExistingPivot($check->id, [ 'bank_account_movement_id' => $movement->id ]))
                // return document error
                return $this->documentError(__('banking::deposit_slip.completeIt.check-movement-link-failed', [
                    'check' => $check->document_number,
                ]));
        }

        // return document completed status
        return self::STATUS_Completed;
    }

}
