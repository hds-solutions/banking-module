<?php

namespace HDSSolutions\Laravel\Models;

use HDSSolutions\Laravel\Traits\BelongsToCompany;

abstract class X_BankAccount extends Base\Model {
    use BelongsToCompany;

    const ACCOUNT_TYPE_CurrentAccount       = 'CA';
    const ACCOUNT_TYPE_SavingsAccount       = 'SA';
    const ACCOUNT_TYPES = [
        self::ACCOUNT_TYPE_CurrentAccount   => 'banking::bank_account.account_type.current_account',
        self::ACCOUNT_TYPE_SavingsAccount   => 'banking::bank_account.account_type.savings_account',
    ];

    protected array $orderBy = [
        'default'           => 'DESC',
        'account_number'    => 'ASC',
    ];

    protected $fillable = [
        'bank_id',
        'account_type',
        'account_number',
        'iban',
        'description',
        'currency_id',
        'pending_balance',
        'current_balance',
        'credit_limit',
        'default',
    ];

    protected $attributes = [
        'pending_balance'   => 0,
        'current_balance'   => 0,
        'default'           => false,
    ];

    protected static array $rules = [
        'bank_id'           => [ 'required' ],
        'account_type'      => [ 'required' ],
        'account_number'    => [ 'required' ],
        'iban'              => [ 'sometimes', 'nullable' ],
        'description'       => [ 'sometimes', 'nullable' ],
        'currency_id'       => [ 'required' ],
        'pending_balance'   => [ 'required', 'numeric' ],
        'current_balance'   => [ 'required', 'numeric' ],
        'credit_limit'      => [ 'required', 'numeric', 'min:0' ],
        'default'           => [ 'required', 'boolean' ],
    ];

    protected $appends = [
        'account_type_pretty',
    ];

    public final function setAccountTypeAttribute(string $account_type):void {
        // validate attribute
        if (!array_key_exists($account_type, self::ACCOUNT_TYPES)) return;
        // set attribute
        $this->attributes['account_type'] = $account_type;
    }

    public final function getAccountTypePrettyAttribute():string {
        // return translated type
        return __( self::ACCOUNT_TYPES[$this->account_type] );
    }

    public function getPendingBalanceAttribute():int|float {
        return $this->attributes['pending_balance'] / pow(10, currency($this->currency_id)->decimals);
    }

    public function setPendingBalanceAttribute(int|float $pending_balance) {
        $this->attributes['pending_balance'] = $pending_balance * pow(10, currency($this->currency_id)->decimals);
    }

    public function getCurrentBalanceAttribute():int|float {
        return $this->attributes['current_balance'] / pow(10, currency($this->currency_id)->decimals);
    }

    public function setCurrentBalanceAttribute(int|float $current_balance) {
        $this->attributes['current_balance'] = $current_balance * pow(10, currency($this->currency_id)->decimals);
    }

    public function getCreditLimitAttribute():int|float {
        return $this->attributes['credit_limit'] / pow(10, currency($this->currency_id)->decimals);
    }

    public function setCreditLimitAttribute(int|float $credit_limit) {
        $this->attributes['credit_limit'] = $credit_limit * pow(10, currency($this->currency_id)->decimals);
    }

}
