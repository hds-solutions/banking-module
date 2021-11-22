<?php return [

    'details'       => [
        'Detalles'
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

];
