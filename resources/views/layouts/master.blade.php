@extends('backend::layouts.master')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset(mix('banking-module/assets/css/app.css')) }}">
@endpush
@push('pre-scripts')
    <script src="{{ asset(mix('banking-module/assets/js/app.js')) }}"></script>
@endpush
