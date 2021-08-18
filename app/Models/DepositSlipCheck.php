<?php

namespace HDSSolutions\Laravel\Models;

use Illuminate\Validation\Validator;

class DepositSlipCheck extends X_DepositSlipCheck {

    public function depositSlip() {
        return $this->belongsTo(DepositSlip::class);
    }

    public function check() {
        return $this->belongsTo(Check::class);
    }

    public function movement() {
        return $this->belongsTo(BankAccountMovement::class, 'bank_account_movement_id');
    }

    protected function beforeSave(Validator $validator) {
        // validate that check is from the same currency
        if ($this->check->currency_id !== $this->depositSlip->bankAccount->currency_id)
            // reject with error
            return $validator->errors()->add('check_id', __('banking::deposit_slip_check.currency-missmatch', [
                'check' => $this->check->document_number,
            ]));
    }

    public function afterSave() {
        // update deposit slip total
        $this->depositSlip->update([
            // add checks amount to cash amount
            'total' => $this->depositSlip->cash_amount
                // add checks amount
                + $this->depositSlip()->checks()->sum('payment_amount') ]);
    }

}
