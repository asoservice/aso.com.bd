@extends('backend.layouts.master')

@section('title', __('static.transaction.transactions'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5>{{ __('static.transaction.transactions') }}</h5>
        </div>
        <div class="card-body common-table">
            <div class="tax-table">
                <div class="table-responsive">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {!! $dataTable->scripts() !!}
@endpush
