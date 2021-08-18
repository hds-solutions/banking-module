<?php

namespace HDSSolutions\Laravel\Models;

use HDSSolutions\Laravel\Traits\HasPartnerable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Validator;

class BankAccountMovement extends X_BankAccountMovement {

    public function bankAccount() {
        return $this->belongsTo(BankAccount::class);
    }

    public function referable() {
        return $this->morphTo();
    }

    public function beforeSave(Validator $validator) {
        // movement_type validations
        switch ($this->movement_type) {
            case self::MOVEMENT_TYPE_TransferIn:
                // TODO: Validate refers to BankAccountMovement
                break;
            case self::MOVEMENT_TYPE_TransferOut:
                // TODO: Validate refers to BankAccountMovement
                break;
            case self::MOVEMENT_TYPE_CheckDeposit:
                // TODO: Validate refers to Check
                break;
            case self::MOVEMENT_TYPE_CashDeposit:
                // TODO: Validate refers to CashLine
                break;
        }
    }

    public function scopeConfirmed(Builder $query, bool $confirmed = true):Builder {
        // return only confirmed movements
        return $query->where('confirmed', $confirmed);
    }

    public function scopePending(Builder $query, bool $pending = true):Builder {
        // return only pending movements (confirmed= !pending)
        return $this->scopeConfirmed($query, !$pending);
    }

    public function afterSave() {
        // update bankAccount balande
        $this->bankAccount->update([
            // set pending balance
            'pending_balance' => $this->bankAccount->movements()->pending()->sum('amount') / pow(10, currency($this->bankAccount->currency_id)->decimals),
            // set current balance
            'current_balance' => $this->bankAccount->movements()->confirmed()->sum('amount') / pow(10, currency($this->bankAccount->currency_id)->decimals),
        ]);
    }

}
