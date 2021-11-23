<?php return [

    'details'       => [
        'Details'
    ],

    'bank_account_id'       => [
        'Cuenta de Banco',
        '_' => 'Cuenta de Banco',
        '?' => 'Cuenta de Banco',
    ],

    'to_bank_account_id'    => [
        'A Cuenta de Banco',
        '_' => 'A Cuenta de Banco',
        '?' => 'A Cuenta de Banco',
    ],

    'cash_id'               => [
        'Caja',
        '_' => 'Caja',
        '?' => 'Caja helper text',
    ],

    'cash_book_id'          => [
        'Libro de Caja',
        '_' => 'Libro de Caja',
        '?' => 'Libro de Caja',
    ],

    'document_number'       => [
        'Número de Documento',
        '_' => 'Número de Documento',
        '?' => 'Número de Documento',
    ],

    'transacted_at'         => [
        'Fecha Transacción',
        '_' => 'Fecha Transacción',
        '?' => 'Fecha Transacción',
    ],

    'cash_amount'           => [
        'Monto Efectivo',
        '_' => 'Monto Efectivo',
        '?' => 'Monto Efectivo',
    ],

    'checks_amount'         => [
        'Monto Cheques',
        '_' => 'Monto Cheques',
        '?' => 'Monto Cheques',
    ],

    'total'                 => [
        'Total',
        '_' => 'Total',
        '?' => 'Total',
    ],

    'document_status'       => [
        'Estado del Documento',
        '_' => 'Estado del Documento',
        '?' => 'Estado del Documento help text',
    ],

    'checks'                => [
        'Cheques',
        '_' => 'Cheques',
        '?' => 'Cheques help text',

        'check_id'          => [
            'Cheque',
            '_' => 'Cheque',
            '?' => 'Cheque help text',
        ],

    ] + __('payments::check'),

    'beforeSave'    => [
        'no-cash-specified' => 'No se especifico la Caja para el monto en efectivo',
    ],

    'prepareIt'     => [
        'no-lines'                  => 'El documento no tiene monto en efectivo o cheques a depositar',
        'cash-not-open'             => 'La caja :cash no está abierta',
        'currency-missmatch'        => 'La moneda de la caja :cash no coincide con la cuenta bancaria :bankAccount',
        'cash-no-balance'           => 'La caja :cash no tiene balance suficiente para el deposito de efectivo',
        'check-expired'             => 'El cheque :check está expirado',
        'check-already-deposited'   => 'El cheque :check ya está depositado en la cuenta bancaria :bankAccount',
        'check-already-cashed'      => 'El cheque :check fue cobrado en efectivo a la caja :cash',
        'check-currency-missmatch'  => 'La moneda del cheque :check no coincide con la cuenta bancaria :bankAccount',
    ],

    'completeIt'    => [
        'cash-line'         => 'Deposito de efectivo en :bankAccount',
        'cash-movement'     => 'Deposito de efectivo desde :cash',
        'check-movement'    => 'Deposito de cheque :check',

        'check-movement-link-failed'    => 'Falló la vinculación del BankAccountMovement en DepositSlipCheck para el cheque :check',
    ],

];
