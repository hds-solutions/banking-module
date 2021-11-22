<?php return [

    'details'           => [
        'Detalles',
    ],

    'bank_id'           => [
        'Banco',
        '_' => 'Banco',
        '?' => 'Banco helper text',
    ],

    'account_type'      => [
        'Tipo de Cuenta',
        '_' => 'Tipo de Cuenta',
        '?' => 'Tipo de Cuenta help text',

        'current_account'   => 'Cuenta Corriente',
        'savings_account'   => 'Caja de Ahorros',
    ],

    'account_number'    => [
        'Número de Cuenta',
        '_' => 'Número de Cuenta',
        '?' => 'Número de Cuenta helper text',
    ],

    'iban'              => [
        'Número IBAN',
        '_' => 'Número IBAN',
        '?' => 'Número IBAN helper text',
    ],

    'description'       => [
        'Descripción',
        '_' => 'Descripción',
        '?' => 'Descripción helper text',
    ],

    'currency_id'       => [
        'Moneda',
        '_' => 'Moneda',
        '?' => 'Moneda helper text',
    ],

    'pending_balance'   => [
        'Balance Pendiente',
        '_' => 'Balance Pendiente',
        '?' => 'Balance Pendiente helper text',
    ],

    'current_balance'   => [
        'Balance Actual',
        '_' => 'Balance Actual',
        '?' => 'Balance Actual helper text',
    ],

    'credit_limit'      => [
        'Limite de Crédito',
        '_' => 'Limite de Crédito',
        '?' => 'Limite de Crédito helper text',
    ],

    'default'       => [
        'Cuenta por defecto',
        '_' => 'Cuenta por defecto',
        '?' => 'Marcar para asignar esta cuenta como cuenta por defecto para el banco actual',
    ],

    'movements'     => [
        'Movimientos',
        '_' => 'Movimientos',
        '?' => 'Movimientos help text',
    ] + __('banking::bank_account_movement'),

];
