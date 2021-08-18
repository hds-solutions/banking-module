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

    ] + Lang::get('payments::check'),

];
