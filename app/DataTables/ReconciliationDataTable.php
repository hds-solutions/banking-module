<?php

namespace HDSSolutions\Laravel\DataTables;

use HDSSolutions\Laravel\Models\Reconciliation as Resource;
use HDSSolutions\Laravel\Traits\DatatableAsDocument;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Column;

class ReconciliationDataTable extends Base\DataTable {
    use DatatableAsDocument;

    protected array $with = [
    ];

    protected array $orderBy = [
        'document_status'   => 'asc',
        'transacted_at'     => 'desc',
    ];

    public function __construct() {
        parent::__construct(
            Resource::class,
            route('backend.reconciliations'),
        );
    }

    protected function getColumns() {
        return [
            Column::computed('id')
                ->title( __('banking::reconciliation.id.0') )
                ->hidden(),

            Column::make('document_number')
                ->title( __('banking::reconciliation.document_number.0') ),

            Column::make('transacted_at_pretty')
                ->title( __('banking::reconciliation.transacted_at.0') ),

            Column::make('document_status_pretty')
                ->title( __('sales::invoice.document_status.0') )
                ->class('text-center'),

            Column::make('actions'),
        ];
    }

}
