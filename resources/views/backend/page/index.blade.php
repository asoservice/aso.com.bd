@extends('backend.layouts.master')

@section('title', __('static.page.pages'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5>{{ __('static.page.pages') }}</h5>
            <div class="btn-action">
            @can('backend.page.create')
                <div class="btn-popup ms-auto mb-0">
                    <a href="{{ route('backend.page.create') }}" class="btn">{{ __('static.page.create') }}
                    </a>
                </div>
            @endcan
            @can('backend.page.destroy')
            <a href="javascript:void(0);" class="btn btn-sm btn-secondary deleteConfirmationBtn"  style="display: none;" data-url="{{ route('backend.delete.pages') }}">
                <span id="count-selected-rows">0</span> {{__('static.delete_selected')}}
            </a>
            @endcan
        </div></div>
        <div class="card-body common-table">
            <div class="page-table">
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
