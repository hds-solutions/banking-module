<?php return [

    'details'       => [
        'Details'
    ],

    'bank_account_id'       => [
        'Bank Account',
        '_' => 'Bank Account',
        '?' => 'Bank Account',
    ],

    'to_bank_account_id'    => [
        'To Bank Account',
        '_' => 'To Bank Account',
        '?' => 'To Bank Account',
    ],

    'cash_id'               => [
        'Cash',
        '_' => 'Cash',
        '?' => 'Cash helper text',
    ],

    'cash_book_id'          => [
        'CashBook',
        '_' => 'CashBook',
        '?' => 'CashBook',
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

    'cash_amount'           => [
        'Cash Amount',
        '_' => 'Cash Amount',
        '?' => 'Cash Amount',
    ],

    'checks_amount'         => [
        'Checks Amount',
        '_' => 'Checks Amount',
        '?' => 'Checks Amount',
    ],

    'total'                 => [
        'Total',
        '_' => 'Total',
        '?' => 'Total',
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

    'beforeSave'    => [
        'no-cash-specified' => 'No Cash was specified for cash amount',
    ],

    'prepareIt'     => [
        'no-lines'                  => 'The document has no cash amount or checks to deposit',
        'cash-not-open'             => 'The Cash :cash is\'nt open',
        'currency-missmatch'        => 'The currency of the cash :cash doesn\'t match with bank account :bankAccount',
        'cash-no-balance'           => 'The cash :cash don\'t has enough balance to deposit cash amount',
        'check-expired'             => 'The check :check is expired',
        'check-already-deposited'   => 'The check :check is already deposited in the bank account :bankAccount',
        'check-already-cashed'      => 'The check :check was cashed in to the Cash :cash',
        'check-currency-missmatch'  => 'The currency of the check :check doesn\'t match with bank account :bankAccount',
    ],

    'completeIt'    => [
        'cash-line'         => 'Cash deposit in :bankAccount',
        'cash-movement'     => 'Cash deposit from :cash',
        'check-movement'    => 'Check deposit of :check',

        'check-movement-link-failed'    => 'Failed to link BankAccountMovement on DepositSlipCheck for check :check',
    ],

];
