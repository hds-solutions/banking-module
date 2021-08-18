<?php

namespace HDSSolutions\Laravel\DataTables;

use HDSSolutions\Laravel\Models\BankAccount as Resource;
use HDSSolutions\Laravel\Traits\DatatableWithCurrency;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Html\Column;

class BankAccountDataTable extends Base\DataTable {
    use DatatableWithCurrency;

    protected array $with = [
        'bank',
        'currency',
    ];

    protected array $orderBy = [
        'bank.name'         => 'asc',
        'default'           => 'asc',
        'account_number'    => 'asc',
    ];

    public function __construct() {
        parent::__construct(
            Resource::class,
            route('backend.bank_accounts'),
        );
    }

    protected function getColumns() {
        return [
            Column::computed('id')
                ->title( __('banking::bank_account.id.0') )
                ->hidden(),

            Column::make('bank.name')
                ->title( __('banking::bank_account.bank_id.0') ),

            Column::make('account_type_pretty')
                ->title( __('banking::bank_account.account_type.0') ),

            Column::make('account_number')
                ->title( __('banking::bank_account.account_number.0') ),

            Column::make('description')
                ->title( __('banking::bank_account.description.0') ),

            Column::make('currency.name')
                ->title( __('banking::bank_account.currency_id.0') ),

            Column::make('pending_balance')
                ->title( __('banking::bank_account.pending_balance.0') )
                ->renderRaw('view:bank_account')
                ->data( view('banking::bank_accounts.datatable.pending_balance')->render() )
                ->class('text-right'),

            Column::make('current_balance')
                ->title( __('banking::bank_account.current_balance.0') )
                ->renderRaw('view:bank_account')
                ->data( view('banking::bank_accounts.datatable.current_balance')->render() )
                ->class('text-right'),

            Column::make('actions'),
        ];
    }

    protected function joins(Builder $query):Builder {
        // add custom JOIN to bank + currency
        return $query
            // JOIN to Bank
            ->join('banks', 'banks.id', 'bank_accounts.bank_id')

            // JOIN to Currency
            ->Join('currencies', 'currencies.id', 'bank_accounts.currency_id');
    }

    protected function orderBankName(Builder $query, string $order):Builder {
        // order by Bank.name
        return $query->orderBy('banks.name', $order);
    }

    protected function filterBank(Builder $query, $bank_id):Builder {
        // filter only from bank
        return $query->where('bank_id', $bank_id);
    }

    protected final function orderAccountType(Builder $query, string $order):Builder {
        // build a raw ORDER BY query
        $orderByRaw = '';
        // invert order
        $order = $order == 'asc' ? 'desc' : 'asc';
        $idx = 0;
        // append BankAccount.account_types with idx as order value
        foreach (Resource::ACCOUNT_TYPES as $account_type => $name) {
            $orderByRaw .= "CASE WHEN bank_accounts.account_type = '$account_type' THEN $idx END $order, ";
            $idx++;
        }

        // return query with custom order
        return $query->orderByRaw( rtrim($orderByRaw, ', ') );
    }

    protected final function orderAccountTypePretty(Builder $query, string $order):Builder {
        // alias to account_type order
        return $this->orderAccountType($query, $order);
    }

    protected final function searchAccountType(Builder $query, string $value):Builder {
        $matches = [];
        // check if value matches some resource account_type string
        foreach (Resource::ACCOUNT_TYPES as $account_type => $name)
            // check if value matches account_type or translation
            if ( stripos($account_type, $value) !== false || stripos(__($name), $value) !== false )
                // add account_type value to matches
                $matches[] = $account_type;

        // filter query with matched statuses
        return count($matches) ? $query->whereIn('bank_accounts.account_type', $matches) : $query;
    }

    protected final function searchAccountTypePretty(Builder $query, string $value):Builder {
        // alias to account_type search
        return $this->searchAccountType($query, $value);
    }

}
