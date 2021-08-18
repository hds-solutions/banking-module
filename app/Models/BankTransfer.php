<?php

namespace HDSSolutions\Laravel\Models;

use HDSSolutions\Laravel\Interfaces\Document;
use HDSSolutions\Laravel\Traits\HasDocumentActions;
use Illuminate\Database\Eloquent\Builder;

class BankTransfer extends A_DepositSlip {

    protected $table = 'deposit_slips';

    public function __construct(array $attributes = []) {
        // redirect attributes to parent
        parent::__construct(is_array($attributes) ? $attributes : [] + [
            // force transaction_type=Transfer
            'transaction_type'  => self::TRANSACTION_TYPE_Transfer,
        ]);
    }

    public function getForeignKey() {
        return Str::snake(class_basename(DepositSlip::class)).'_'.$this->getKeyName();
    }

    protected static function booted() {
        static::addGlobalScope('bank_transfer', fn(Builder $query) => $query->where('transaction_type', self::TRANSACTION_TYPE_Transfer));
    }

    public function setTransactionTypeAttribute(string $ignored):void {
        parent::setTransactionTypeAttribute(self::TRANSACTION_TYPE_Transfer);
    }

    public function toBankAccount() {
        return $this->belongsTo(BankAccount::class, 'to_bank_account_id');
    }

}
