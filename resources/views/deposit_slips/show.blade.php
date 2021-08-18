@extends('banking::layouts.master')

@section('page-name', __('banking::deposit_slips.title'))
@section('description', __('banking::deposit_slips.description'))

@section('content')

<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <i class="fas fa-user-plus mr-2"></i>
                @lang('banking::deposit_slips.show')
            </div>
            <div class="col-6 d-flex justify-content-end">
                @if (!$resource->isCompleted())
                <a href="{{ route('backend.deposit_slips.edit', $resource) }}"
                    class="btn btn-sm ml-2 btn-outline-info">@lang('banking::deposit_slips.edit')</a>
                @endif
                <a href="{{ route('backend.deposit_slips.create') }}"
                    class="btn btn-sm ml-2 btn-outline-primary">@lang('banking::deposit_slips.create')</a>
            </div>
        </div>
    </div>
    <div class="card-body">

        @include('backend::components.errors')

        <div class="row">
            <div class="col-12 col-xl-6">

                <div class="row">
                    <div class="col">
                        <h2>@lang('banking::deposit_slip.details.0')</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col">@lang('banking::deposit_slip.document_number.0'):</div>
                    <div class="col h4 font-weight-bold">{{ $resource->document_number }}</div>
                </div>

                <div class="row">
                    <div class="col">@lang('banking::deposit_slip.bank_account_id.0'):</div>
                    <div class="col h4 font-weight-bold">{{ $resource->bankAccount->account_number }} <small class="font-weight-light">[{{ $resource->bankAccount->bank->name }}]</small></div>
                </div>

                <div class="row">
                    <div class="col">@lang('banking::deposit_slip.transacted_at.0'):</div>
                    <div class="col h4">{{ pretty_date($resource->transacted_at, true) }}</div>
                </div>

                <div class="row">
                    <div class="col">@lang('banking::deposit_slip.cash_amount.0'):</div>
                    <div class="col h4">{{ currency($resource->bankAccount->currency_id)->code }} <b>{{ number($resource->cash_amount, currency($resource->bankAccount->currency_id)->decimals) }}</b>@if ($resource->cash_amount > 0) <small class="font-weight-light">[{{ $resource->cash->cashBook->name }}]</small>@endif</div>
                </div>

                <div class="row">
                    <div class="col">@lang('banking::deposit_slip.document_status.0'):</div>
                    <div class="col h4 mb-0">{{ Document::__($resource->document_status) }}</div>
                </div>

            </div>
        </div>

        <div class="row py-5">
            <div class="col">

                <div class="row">
                    <div class="col">
                        <h2 class="mb-0">@lang('banking::deposit_slip.checks.0')</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col">

                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-borderless table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="align-middle">{{-- @lang('banking::deposit_slip.document_number.0') --}}</th>
                                        <th class="align-middle">@lang('banking::deposit_slip.checks.document_number.0')</th>
                                        <th class="align-middle">@lang('banking::deposit_slip.checks.account_holder.0')</th>
                                        <th class="align-middle">@lang('banking::deposit_slip.checks.due_date.0')</th>
                                        <th class="align-middle text-right">@lang('banking::deposit_slip.checks.payment_amount.0')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($resource->checks as $check)
                                        <tr>
                                            <td class="align-middle">{{ $check->bank->name }}</td>
                                            <td class="align-middle">
                                                <a href="{{ route('backend.checks.show', $check) }}"
                                                    class="text-secondary text-decoration-none">{{ $check->document_number }}<small class="ml-2 text-dark">{{ $check->transacted_at_pretty }}</small></a>
                                            </td>
                                            <td class="align-middle">{{ $check->account_holder }}</td>
                                            <td class="align-middle">{{ pretty_date($check->due_date) }}</td>
                                            <td class="align-middle text-right">{{ currency($check->currency_id)->code }} <b>{{ number($check->payment_amount, currency($check->currency_id)->decimals) }}</b></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col text-right">
                        <h4 class="pr-1 mb-0">{{ currency($resource->bankAccount->currency_id)->code }} <b>{{ number($resource->total, currency($resource->bankAccount->currency_id)->decimals) }}</b></h4>
                    </div>
                </div>

            </div>

        </div>

        @include('backend::components.document-actions', [
            'route'     => 'backend.deposit_slips.process',
            'resource'  => $resource,
        ])

    </div>
</div>

@endsection
