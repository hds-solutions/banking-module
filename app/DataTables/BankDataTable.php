<?php

namespace HDSSolutions\Laravel\DataTables;

use HDSSolutions\Laravel\Models\Bank as Resource;
use Yajra\DataTables\Html\Column;

class BankDataTable extends Base\DataTable {

    public function __construct() {
        parent::__construct(
            Resource::class,
            route('backend.banks'),
        );
    }

    protected function getColumns() {
        return [
            Column::computed('id')
                ->title( __('bank::bank.id.0') )
                ->hidden(),

            Column::make('name')
                ->title( __('bank::bank.name.0') ),

            Column::make('actions'),
        ];
    }

}
