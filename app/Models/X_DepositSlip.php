<?php

namespace HDSSolutions\Laravel\Models;

use HDSSolutions\Laravel\Traits\BelongsToCompany;

abstract class X_DepositSlip extends Base\Model {
    use BelongsToCompany;

    const TRANSACTION_TYPE_Deposit  = 'DE';
    const TRANSACTION_TYPE_Transfer = 'TR';
    const TRANSACTION_TYPES = [
        self::TRANSACTION_TYPE_Deposit  => 'banking::deposit_slip.transaction_type.deposit',
        self::TRANSACTION_TYPE_Transfer => 'banking::deposit_slip.transaction_type.transfer',
    ];

    protected array $orderBy = [
        'transacted_at'     => 'DESC',
    ];

    protected $fillable = [
        'bank_account_id',
        'to_bank_account_id',
        'cash_id',
        'transaction_type',
        'document_number',
        'transacted_at',
        'conversion_rate_id',
        'rate',
        'cash_amount',
        'bank_account_movement_id',
        'total',
    ];

    protected $appends = [
        'transacted_at_pretty',
    ];

    protected static array $rules = [
        'bank_account_id'       => [ 'required' ],
        'to_bank_account_id'    => [ 'sometimes', 'nullable' ],
        'cash_id'               => [ 'sometimes', 'nullable' ],
        'transaction_type'      => [ 'required' ],
        'document_number'       => [ 'required' ],
        'transacted_at'         => [ 'required' ],
        'conversion_rate_id'    => [ 'sometimes', 'nullable' ],
        'rate'                  => [ 'sometimes', 'nullable', 'numeric', 'min:0' ],
        'cash_amount'           => [ 'required', 'numeric', 'min:0' ],
        'bank_account_movement_id'  => [ 'sometimes', 'nullable' ],
        'total'                 => [ 'required', 'numeric', 'min:0' ],
    ];

    public function setTransactionTypeAttribute(string $transaction_type):void {
        // validate attribute
        if (!array_key_exists($transaction_type, self::TRANSACTION_TYPES)) return;
        // set attribute
        $this->attributes['transaction_type'] = $transaction_type;
    }

    public function getTransactedAtPrettyAttribute():string {
        return pretty_date($this->transacted_at, true);
    }

    public function getCashAmountAttribute():int|float {
        return $this->attributes['cash_amount'] / pow(10, currency($this->bankAccount->currency_id)->decimals);
    }

    public function setCashAmountAttribute(int|float $cash_amount) {
        $this->attributes['cash_amount'] = $cash_amount * pow(10, currency($this->bankAccount->currency_id)->decimals);
    }

    public function getTotalAttribute():int|float {
        return $this->attributes['total'] / pow(10, currency($this->bankAccount->currency_id)->decimals);
    }

    public function setTotalAttribute(int|float $total) {
        $this->attributes['total'] = $total * pow(10, currency($this->bankAccount->currency_id)->decimals);
    }

}
