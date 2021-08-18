<?php

namespace HDSSolutions\Laravel\Models;

use HDSSolutions\Laravel\Interfaces\Document;
use HDSSolutions\Laravel\Traits\HasDocumentActions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Validator;

abstract class A_DepositSlip extends X_DepositSlip implements Document {
    use HasDocumentActions;

    public final static function nextDocumentNumber():string {
        // return next document number for specified stamping
        return str_increment(self::withTrashed()->max('document_number') ?? null);
    }

    public final function bankAccount() {
        return $this->belongsTo(BankAccount::class);
    }

    public final function cash() {
        return $this->belongsTo(Cash::class);
    }

    public final function conversionRate() {
        return $this->belongsTo(ConversionRate::class);
    }

    protected function beforeSave(Validator $validator) {
        // cash amount must have value
        $this->cash_amount = $this->cash_amount ?? 0;
    }

}
