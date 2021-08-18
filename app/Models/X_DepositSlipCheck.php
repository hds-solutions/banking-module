<?php

namespace HDSSolutions\Laravel\Models;

abstract class X_DepositSlipCheck extends Base\Pivot {

    protected $table = 'deposit_slip_check';

    protected $fillable = [
        'deposit_slip_id',
        'check_id',
        'bank_account_movement_id',
    ];

    protected static array $rules = [
        'deposit_slip_id'   => [ 'required' ],
        'check_id'          => [ 'required' ],
        'bank_account_movement_id'  => [ 'sometimes', 'nullable' ],
    ];

}
