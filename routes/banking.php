<?php

use HDSSolutions\Laravel\Http\Controllers\{
    BankController,
    BankAccountController,
    BankAccountMovementController,
    DepositSlipController,
    ReconciliationController,
};
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'        => config('backend.prefix'),
    'middleware'    => [ 'web', 'auth:'.config('backend.guard') ],
], function() {
    // name prefix
    $name_prefix = [ 'as' => 'backend' ];

    Route::resource('banks',                BankController::class,          $name_prefix)
        ->parameters([ 'banks' => 'resource' ])
        ->name('index', 'backend.banks');

    Route::resource('bank_accounts',        BankAccountController::class,   $name_prefix)
        ->parameters([ 'bank_accounts' => 'resource' ])
        ->name('index', 'backend.bank_accounts');
    Route::resource('bank_account_movements',   BankAccountMovementController::class,  $name_prefix)
        ->only([ 'create', 'store' ])
        ->parameters([ 'bank_account_movements' => 'resource' ])
        ->name('index', 'backend.bank_account_movements');

    Route::resource('deposit_slips',        DepositSlipController::class,   $name_prefix)
        ->parameters([ 'deposit_slips' => 'resource' ])
        ->name('index', 'backend.deposit_slips');
    Route::post('deposit_slips/{resource}/process', [ DepositSlipController::class, 'processIt'])
        ->name('backend.deposit_slips.process');

    Route::resource('reconciliations', ReconciliationController::class,   $name_prefix)
        ->parameters([ 'reconciliations' => 'resource' ])
        ->name('index', 'backend.reconciliations');
    Route::post('reconciliations/{resource}/process', [ ReconciliationController::class, 'processIt'])
        ->name('backend.reconciliations.process');

});
