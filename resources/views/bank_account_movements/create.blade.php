@extends('backend::layouts.master')

@section('page-name', __('banking::bank_account_movements.title'))

@section('content')

<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <i class="fas fa-company-plus"></i>
                @lang('banking::bank_account_movements.create')
            </div>
            <div class="col-6 d-flex justify-content-end">
                {{-- <a href="{{ route('backend.bank_account_movements.create') }}"
                    class="btn btn-sm btn-outline-primary">@lang('banking::bank_account_movements.create')</a> --}}
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('backend.bank_account_movements.store') }}" enctype="multipart/form-data">
            @csrf
            @onlyform
            @include('banking::bank_account_movements.form')
        </form>
    </div>
</div>

@endsection
