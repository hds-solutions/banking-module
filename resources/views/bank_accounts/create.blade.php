@extends('banking::layouts.master')

@section('page-name', __('banking::bank_accounts.title'))

@section('content')

<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <i class="fas fa-company-plus"></i>
                @lang('banking::bank_accounts.create')
            </div>
            <div class="col-6 d-flex justify-content-end">
                {{-- <a href="{{ route('backend.bank_accounts.create') }}"
                    class="btn btn-sm btn-primary">@lang('banking::bank_accounts.create')</a> --}}
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('backend.bank_accounts.store') }}" enctype="multipart/form-data">
            @csrf
            @onlyform
            @include('banking::bank_accounts.form')
        </form>
    </div>
</div>

@endsection
