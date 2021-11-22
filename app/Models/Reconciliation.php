<?php

namespace HDSSolutions\Laravel\Models;

use HDSSolutions\Laravel\Interfaces\Document;
use HDSSolutions\Laravel\Traits\HasDocumentActions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Validator;

class Reconciliation extends X_Reconciliation implements Document {
    use HasDocumentActions;

    public static function nextDocumentNumber():?string {
        // return next document number for specified stamping
        return str_increment(self::withTrashed()->max('document_number'));
    }

    public function checks() {
        return $this->belongsToMany(Check::class, 'reconciliation_check')
            ->using(ReconciliationCheck::class)
            ->withTimestamps()
            ->as('reconciliationCheck');
    }

    public function prepareIt():?string {
        // validate checks currency and deposited status
        foreach ($this->checks as $check) {
            // validate that check is deposited
            if (!$check->is_deposited)
                // reject document, check not deposited
                return $this->documentError('banking::reconciliation_check.not-deposited', [
                    'check' => $check->document_number,
                ]);

            // validate that check isn't paid
            if ($check->is_paid)
                // reject document, check not deposited
                return $this->documentError('banking::reconciliation_check.already-paid', [
                    'check' => $check->document_number,
                ]);
        }

        // return status InProgress
        return Document::STATUS_InProgress;
    }

    public final function completeIt():?string {
        // mark checks as deposited
        foreach ($this->checks as $check) {
            // deposit check to bank account
            if (!$check->update([
                // mark check as paid
                'is_paid'   => true,
            ]))
                // return document error
                return $this->documentError( $check->errors()->first() );

            // update bankAccount movement status
            if (!$check->movement->update([
                // set movement as confirmed
                'confirmed' => true
            ]))
                // return document error
                return $this->documentError( $check->movement->errors()->first() );
        }

        // return document completed status
        return Document::STATUS_Completed;
    }

}
