<?php

namespace HDSSolutions\Laravel\DataTables;

use HDSSolutions\Laravel\Models\DepositSlip as Resource;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Column;

class DepositSlipDataTable extends Base\DataTable {

    protected array $with = [
        'bankAccount.bank',
        'bankAccount.currency',
        'cash',
    ];

    protected array $orderBy = [
        'transacted_at'     => 'desc',
    ];

    public function __construct() {
        parent::__construct(
            Resource::class,
            route('backend.deposit_slips'),
        );
    }

    protected function getColumns() {
        return [
            Column::computed('id')
                ->title( __('banking::deposit_slip.id.0') )
                ->hidden(),

            Column::make('document_number')
                ->title( __('banking::deposit_slip.document_number.0') ),

            Column::make('bank_account')
                ->renderRaw('view:deposit_slip')
                ->data( view('banking::deposit_slips.datatable.bank_account')->render() )
                ->title( __('banking::deposit_slip.bank_account_id.0') ),

            Column::make('transacted_at_pretty')
                ->title( __('banking::deposit_slip.transacted_at.0') ),

            Column::make('cash_amount')
                ->title( __('banking::deposit_slip.cash_amount.0') )
                ->renderRaw('view:deposit_slip')
                ->data( view('banking::deposit_slips.datatable.cash_amount')->render() )
                ->class('text-right'),

            Column::make('checks_amount')
                ->title( __('banking::deposit_slip.checks_amount.0') )
                ->renderRaw('view:deposit_slip')
                ->data( view('banking::deposit_slips.datatable.checks_amount')->render() )
                ->class('text-right'),

            Column::make('total')
                ->title( __('banking::deposit_slip.total.0') )
                ->renderRaw('view:deposit_slip')
                ->data( view('banking::deposit_slips.datatable.total')->render() )
                ->class('text-right'),

            Column::make('document_status_pretty')
                ->title( __('sales::invoice.document_status.0') )
                ->class('text-center'),

            Column::make('actions'),
        ];
    }

    // protected function joins(Builder $query):Builder {
    //     // add custom JOIN to bank + currency
    //     return $query
    //         // JOIN to Bank
    //         ->join('banks', 'banks.id', 'deposit_slips.bank_id')

    //         // JOIN to Currency
    //         ->Join('currencies', 'currencies.id', 'deposit_slips.currency_id');
    // }

    // protected function orderBankName(Builder $query, string $order):Builder {
    //     //
    //     return $query->orderBy('banks.name', $order);
    // }

}
