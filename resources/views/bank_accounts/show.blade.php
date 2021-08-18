@extends('backend::layouts.master')

@section('page-name', __('banking::bank_accounts.title'))
@section('description', __('banking::bank_accounts.description'))

@section('content')

<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <i class="fas fa-user-plus mr-2"></i>
                @lang('banking::bank_accounts.show')
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="{{ route('backend.bank_account_movements.create', [ 'bank_account' => $resource ]) }}"
                    class="btn btn-sm ml-2 btn-outline-success">@lang('banking::bank_account_movements.create')</a>
                <a href="{{ route('backend.bank_accounts.edit', $resource) }}"
                    class="btn btn-sm ml-2 btn-outline-info">@lang('banking::bank_accounts.edit')</a>
                <a href="{{ route('backend.bank_accounts.create') }}"
                    class="btn btn-sm ml-2 btn-outline-primary">@lang('banking::bank_accounts.create')</a>
            </div>
        </div>
    </div>
    <div class="card-body">

        @include('backend::components.errors')

        <div class="row">
            <div class="col">
                <h2>@lang('banking::bank_account.details.0')</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-6">

                <div class="row">
                    <div class="col">@lang('banking::bank_account.bank_id.0'):</div>
                    <div class="col h4">{{ $resource->bank->name }}</div>
                </div>

                <div class="row">
                    <div class="col">@lang('banking::bank_account.account_number.0'):</div>
                    <div class="col h4 font-weight-bold">{{ $resource->account_number }}@if ($resource->iban) <small>[{{ $resource->iban }}]</small>@endif</div>
                </div>

                @if ($resource->description)
                <div class="row">
                    <div class="col">@lang('banking::bank_account.description.0'):</div>
                    <div class="col h4">{{ $resource->description }}</div>
                </div>
                @endif

                <div class="row">
                    <div class="col">@lang('banking::bank_account.currency_id.0'):</div>
                    <div class="col h4">{{ currency($resource->currency_id)->name }}</div>
                </div>

                <div class="row">
                    <div class="col">@lang('banking::bank_account.pending_balance.0'):</div>
                    <div class="col h4">{{ amount($resource->pending_balance, currency($resource->currency_id)) }}</div>
                </div>

                <div class="row">
                    <div class="col">@lang('banking::bank_account.current_balance.0'):</div>
                    <div class="col h4">{{ currency($resource->currency_id)->code }} <b>{{ number($resource->current_balance, currency($resource->currency_id)->decimals) }}</b></div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col">
                <h2>@lang('banking::bank_account.movements.0')</h2>
            </div>
        </div>

        <div class="row">
            <div class="col">

                <div class="table-responsive">
                    <table class="table table-sm table-striped table-borderless table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>@lang('banking::bank_account.movements.transacted_at.0')</th>
                                <th>@lang('banking::bank_account.movements.movement_type.0')</th>
                                <th colspan="2">@lang('banking::bank_account.movements.description.0')</th>
                                {{-- <th>@lang('banking::bank_account.movements.referable.0')</th> --}}
                                <th class="text-right">@lang('banking::bank_account.movements.amount.0')</th>
                                <th class="text-right">@lang('banking::bank_account.current_balance.0')</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $balance = $resource->current_balance; ?>
                            @foreach ($resource->movements as $movement)
                                <tr class="@if ($movement->amount < 0) text-danger @endif @if (!$movement->confirmed) text-gray-500 @endif">
                                    <td class="align-middle">{{ pretty_date($movement->transacted_at, true) }}</td>
                                    <td class="align-middle">{{ __(BankAccountMovement::MOVEMENT_TYPES[$movement->movement_type]) }}@if (!$movement->confirmed) <small class="text-gray-500">[@lang('pending')]</small> @endif</td>
                                    <td class="align-middle">{{ $movement->description }}</td>
                                    <td class="align-middle">
                                        <a href="{{ $movement->referable ? match($movement->movement_type) {
                                            BankAccountMovement::MOVEMENT_TYPE_TransferIn   => route('backend.bank_accounts.show', $movement->referable->id),
                                            BankAccountMovement::MOVEMENT_TYPE_TransferOut  => route('backend.bank_accounts.show', $movement->referable->id),
                                            BankAccountMovement::MOVEMENT_TYPE_Difference   => '#',
                                            BankAccountMovement::MOVEMENT_TYPE_CashDeposit  => route('backend.cashes.show', $movement->referable->cash_id),
                                            BankAccountMovement::MOVEMENT_TYPE_CheckDeposit => route('backend.checks.show', $movement->referable->id),
                                            default => null,
                                        } : '#' }}" class="text-decoration-none @if ($movement->confirmed) @if ($movement->amount < 0) text-danger @else text-dark @endif @else text-gray-500 @endif"><b>{!! $movement->referable ? match($movement->movement_type) {
                                            BankAccountMovement::MOVEMENT_TYPE_TransferIn   => $movement->referable->bank->name.'<small class="ml-2">'.$movement->referable->account_number.'</small>',
                                            BankAccountMovement::MOVEMENT_TYPE_TransferOut  => $movement->referable->bank->name.'<small class="ml-2">'.$movement->referable->account_number.'</small>',
                                            // BankAccountMovement::MOVEMENT_TYPE_Difference   => '--',
                                            BankAccountMovement::MOVEMENT_TYPE_CashDeposit  => $movement->referable->cash->cashBook->name,
                                            BankAccountMovement::MOVEMENT_TYPE_CheckDeposit => $movement->referable->bank->name.'<small class="ml-2">'.$movement->referable->document_number.'</small>',
                                            default => null,
                                        } : null !!}</b></a>
                                    </td>
                                    <td class="align-middle text-right">{{ currency($resource->currency_id)->code }} <b>{{ number($movement->amount, currency($resource->currency_id)->decimals) }}</b></td>
                                    <td class="align-middle text-right">{{ currency($resource->currency_id)->code }} <b>{{ number($balance, currency($resource->currency_id)->decimals) }}</b></td>
                                </tr>
                                <?php if ($movement->confirmed) $balance -= $movement->amount; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
