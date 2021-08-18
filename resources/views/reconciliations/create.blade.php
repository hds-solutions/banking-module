@extends('banking::layouts.master')

@section('page-name', __('banking::reconciliations.title'))

@section('content')

<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <i class="fas fa-company-plus"></i>
                @lang('banking::reconciliations.create')
            </div>
            <div class="col-6 d-flex justify-content-end">
                {{-- <a href="{{ route('backend.reconciliations.create') }}"
                    class="btn btn-sm btn-outline-primary">@lang('banking::reconciliations.create')</a> --}}
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('backend.reconciliations.store') }}" enctype="multipart/form-data">
            @csrf
            @onlyform
            @include('banking::reconciliations.form')
        </form>
    </div>
</div>

@endsection
