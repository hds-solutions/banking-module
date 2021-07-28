@extends('backend::layouts.master')

@section('page-name', __('bank::banks.title'))

@section('content')

<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <i class="fas fa-company-plus"></i>
                @lang('bank::banks.create')
            </div>
            <div class="col-6 d-flex justify-content-end">
                {{-- <a href="{{ route('backend.banks.create') }}"
                    class="btn btn-sm btn-primary">@lang('bank::banks.create')</a> --}}
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('backend.banks.store') }}" enctype="multipart/form-data">
            @csrf
            @onlyform
            @include('bank::banks.form')
        </form>
    </div>
</div>

@endsection
