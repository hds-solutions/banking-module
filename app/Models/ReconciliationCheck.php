<?php

namespace HDSSolutions\Laravel\Models;

use Illuminate\Validation\Validator;

class ReconciliationCheck extends X_ReconciliationCheck {

    public function reconciliation() {
        return $this->belongsTo(Reconciliation::class);
    }

    public function check() {
        return $this->belongsTo(Check::class);
    }

}
