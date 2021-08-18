@extends('banking::layouts.master')

@section('page-name', __('banking::reconciliations.title'))
@section('description', __('banking::reconciliations.description'))

@section('content')

<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <i class="fas fa-user-plus mr-2"></i>
                @lang('banking::reconciliations.show')
            </div>
            <div class="col-6 d-flex justify-content-end">
                @if (!$resource->isCompleted())
                <a href="{{ route('backend.reconciliations.edit', $resource) }}"
                    class="btn btn-sm ml-2 btn-outline-info">@lang('banking::reconciliations.edit')</a>
                @endif
                <a href="{{ route('backend.reconciliations.create') }}"
                    class="btn btn-sm ml-2 btn-outline-primary">@lang('banking::reconciliations.create')</a>
            </div>
        </div>
    </div>
    <div class="card-body">

        @include('backend::components.errors')

        <div class="row">
            <div class="col-12 col-xl-6">

                <div class="row">
                    <div class="col">
                        <h2>@lang('banking::reconciliation.details.0')</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col">@lang('banking::reconciliation.document_number.0'):</div>
                    <div class="col h4 font-weight-bold">{{ $resource->document_number }}</div>
                </div>

                <div class="row">
                    <div class="col">@lang('banking::reconciliation.transacted_at.0'):</div>
                    <div class="col h4">{{ pretty_date($resource->transacted_at, true) }}</div>
                </div>

                <div class="row">
                    <div class="col">@lang('banking::reconciliation.document_status.0'):</div>
                    <div class="col h4 mb-0">{{ Document::__($resource->document_status) }}</div>
                </div>

            </div>
        </div>

        <div class="row py-5">
            <div class="col">

                <div class="row">
                    <div class="col">
                        <h2 class="mb-0">@lang('banking::reconciliation.checks.0')</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col">

                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-borderless table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="align-middle">{{-- @lang('banking::reconciliation.document_number.0') --}}</th>
                                        <th class="align-middle">@lang('banking::reconciliation.checks.document_number.0')</th>
                                        <th class="align-middle">@lang('banking::reconciliation.checks.account_holder.0')</th>
                                        <th class="align-middle">@lang('banking::reconciliation.checks.due_date.0')</th>
                                        <th class="align-middle">@lang('banking::reconciliation.checks.is_deposited.0')</th>
                                        <th class="align-middle text-right">@lang('banking::reconciliation.checks.payment_amount.0')</th>
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
                                            <td class="align-middle">{{ $check->bankAccount->account_number }}<small class="ml-2 text-dark">[{{ $check->bankAccount->bank->name }}]</small></td>
                                            <td class="align-middle text-right">{{ currency($check->currency_id)->code }} <b>{{ number($check->payment_amount, currency($check->currency_id)->decimals) }}</b></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>

        </div>

        @include('backend::components.document-actions', [
            'route'     => 'backend.reconciliations.process',
            'resource'  => $resource,
        ])

    </div>
</div>

@endsection
