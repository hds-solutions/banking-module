<?php

namespace HDSSolutions\Laravel\Models;

use Illuminate\Validation\Validator;

class BankAccount extends X_BankAccount {

    public function bank() {
        return $this->belongsTo(Bank::class);
    }

    public function currency() {
        return $this->belongsTo(Currency::class);
    }

    public function movements() {
        return $this->hasMany(BankAccountMovement::class)
            ->ordered();
    }

    protected function beforeSave(Validator $validator) {
        // check if is the only account on bank
        if ($this->bank->accounts()->whereKeyNot( $this->id )->count() > 0) return;
        // force default because is the only one
        $this->default = true;
    }

    protected function afterSave() {
        // check if resource was set as default
        if (!$this->default) return;
        // update accounts on bank
        $this->bank->accounts()
            // filter current resource
            ->whereKeyNot( $this->id )
            // remove default flag
            ->update([ 'default' => false ]);
    }

}
