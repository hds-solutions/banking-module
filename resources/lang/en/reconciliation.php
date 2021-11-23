<?php return [

    'details'       => [
        'Details'
    ],

    'document_number'       => [
        'Document Number',
        '_' => 'Document Number',
        '?' => 'Document Number',
    ],

    'transacted_at'         => [
        'Transacted At',
        '_' => 'Transacted At',
        '?' => 'Transacted At',
    ],

    'document_status'       => [
        'Document Status',
        '_' => 'Document Status',
        '?' => 'Document Status help text',
    ],

    'checks'                => [
        'Checks',
        '_' => 'Checks',
        '?' => 'Checks help text',

        'check_id'          => [
            'Check',
            '_' => 'Check',
            '?' => 'Check help text',
        ],

    ] + __('payments::check'),

    'prepareIt'     => [
        'check-not-deposited'   => 'The Check :check is not deposited',
        'check-already-paid'    => 'The Check :check is already mark as paid',
    ],

];
