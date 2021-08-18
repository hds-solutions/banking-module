<?php

namespace HDSSolutions\Laravel\Models;

abstract class X_ReconciliationCheck extends Base\Pivot {

    protected $table = 'reconciliation_check';

    protected $fillable = [
        'reconciliation_id',
        'check_id',
    ];

    protected static array $rules = [
        'reconciliation_id' => [ 'required' ],
        'check_id'          => [ 'required' ],
    ];

}
