@extends('backend::layouts.master')

@section('page-name', __('banking::banks.title'))

@section('content')

<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <i class="fas fa-company-plus"></i>
                @lang('banking::banks.edit')
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="{{ route('backend.banks.create') }}"
                    class="btn btn-sm btn-primary">@lang('banking::banks.create')</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('backend.banks.update', $resource) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('banking::banks.form')
        </form>
    </div>
</div>

@endsection
