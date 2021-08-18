<?php

namespace HDSSolutions\Laravel\Models;

use HDSSolutions\Laravel\Traits\BelongsToCompany;

abstract class X_Reconciliation extends Base\Model {
    use BelongsToCompany;

    protected array $orderBy = [
        'transacted_at'     => 'DESC',
    ];

    protected $fillable = [
        'document_number',
        'transacted_at',
    ];

    protected $appends = [
        'transacted_at_pretty',
    ];

    protected static array $rules = [
        'document_number'       => [ 'required' ],
        'transacted_at'         => [ 'required' ],
    ];

    public function getTransactedAtPrettyAttribute():string {
        return pretty_date($this->transacted_at, true);
    }

}
