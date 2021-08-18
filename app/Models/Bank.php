<?php

namespace HDSSolutions\Laravel\Models;

class Bank extends X_Bank {

    public function accounts() {
        return $this->hasMany(BankAccount::class)
            ->ordered();
    }

}
