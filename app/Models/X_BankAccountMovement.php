<?php

namespace HDSSolutions\Laravel\Models;

use HDSSolutions\Laravel\Traits\BelongsToCompany;

abstract class X_BankAccountMovement extends Base\Model {
    use BelongsToCompany;

    const MOVEMENT_TYPE_TransferIn      = 'T+';
    const MOVEMENT_TYPE_TransferOut     = 'T-';
    const MOVEMENT_TYPE_Difference      = 'DF';
    const MOVEMENT_TYPE_CheckDeposit    = 'CH';
    const MOVEMENT_TYPE_CashDeposit     = 'CA';
    const MOVEMENT_TYPES = [
        self::MOVEMENT_TYPE_TransferIn      => 'banking::bank_account_movement.movement_type.transfer_in',
        self::MOVEMENT_TYPE_TransferOut     => 'banking::bank_account_movement.movement_type.transfer_out',
        self::MOVEMENT_TYPE_Difference      => 'banking::bank_account_movement.movement_type.difference',
        self::MOVEMENT_TYPE_CheckDeposit    => 'banking::bank_account_movement.movement_type.check_deposit',
        self::MOVEMENT_TYPE_CashDeposit     => 'banking::bank_account_movement.movement_type.cash_deposit',
    ];

    protected $orderBy = [
        'transacted_at' => 'DESC',
    ];

    protected $fillable = [
        'company_id',
        'bank_account_id',
        'movement_type',
        'description',
        'transacted_at',
        'amount',
        'bank_account_movementable_type',
        'bank_account_movementable_id',
        'confirmed',
    ];

    protected $casts = [
        'confirmed' => 'boolean',
    ];

    protected static $rules = [
        'bank_account_id'   => [ 'required' ],
        'movement_type'     => [ 'required' ],
        'description'       => [ 'required' ],
        'transacted_at'     => [ 'required', 'date', 'before_or_equal:now' ],
        'amount'            => [ 'required', 'numeric' ],
        'bank_account_movementable_type'    => [ 'sometimes', 'nullable' ],
        'bank_account_movementable_id'      => [ 'sometimes', 'nullable' ],
        'confirmed'         => [ 'required', 'boolean' ],
    ];

    protected $attributes = [
        'confirmed'     => false,
    ];

    public final function setMovementTypeAttribute(string $movement_type):void {
        // validate attribute
        if (!array_key_exists($movement_type, self::MOVEMENT_TYPES)) return;
        // set attribute
        $this->attributes['movement_type'] = $movement_type;
    }

    public function getAmountAttribute():int|float {
        return $this->attributes['amount'] / pow(10, currency($this->bankAccount->currency_id)->decimals);
    }

    public function setAmountAttribute(int|float $amount) {
        $this->attributes['amount'] = $amount * pow(10, currency($this->bankAccount->currency_id)->decimals);
    }

}
