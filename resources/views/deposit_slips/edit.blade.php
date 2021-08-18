@extends('banking::layouts.master')

@section('page-name', __('banking::deposit_slips.title'))

@section('content')

<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <i class="fas fa-company-plus"></i>
                @lang('banking::deposit_slips.edit')
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="{{ route('backend.deposit_slips.create') }}"
                    class="btn btn-sm btn-outline-primary">@lang('banking::deposit_slips.create')</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('backend.deposit_slips.update', $resource) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('banking::deposit_slips.form')
        </form>
    </div>
</div>

@endsection
