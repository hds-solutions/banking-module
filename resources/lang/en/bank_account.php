<?php return [

    'details'           => [
        'Details',
    ],

    'bank_id'           => [
        'Bank',
        '_' => 'Bank',
        '?' => 'Bank helper text',
    ],

    'account_type'      => [
        'Account Type',
        '_' => 'Account Type',
        '?' => 'Account Type help text',

        'current_account'   => 'Current Account',
        'savings_account'   => 'Savings Account',
    ],

    'account_number'    => [
        'Account Number',
        '_' => 'Account Number',
        '?' => 'Account Number helper text',
    ],

    'iban'              => [
        'IBAN Number',
        '_' => 'IBAN Number',
        '?' => 'IBAN Number helper text',
    ],

    'description'       => [
        'Description',
        '_' => 'Description',
        '?' => 'Description helper text',
    ],

    'currency_id'       => [
        'Currency',
        '_' => 'Currency',
        '?' => 'Currency helper text',
    ],

    'pending_balance'   => [
        'Pending Balance',
        '_' => 'Pending Balance',
        '?' => 'Pending Balance helper text',
    ],

    'current_balance'   => [
        'Current Balance',
        '_' => 'Current Balance',
        '?' => 'Current Balance helper text',
    ],

    'credit_limit'      => [
        'Credit Limit',
        '_' => 'Credit Limit',
        '?' => 'Credit Limit helper text',
    ],

    'default'       => [
        'Default',
        '_' => 'Default',
        '?' => 'Check this to set Account as default for current Bank',
    ],

    'movements'     => [
        'Movements',
        '_' => 'Movements',
        '?' => 'Movements help text',
    ] + include('bank_account_movement.php'),

];
