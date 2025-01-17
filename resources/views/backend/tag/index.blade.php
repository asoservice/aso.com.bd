@extends('backend.layouts.master')

@section('title', __('static.tag.tags'))

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5>{{ __('static.tag.tags') }}</h5>
            <div class="btn-action">
            @can('backend.tag.create')
                <div class="btn-popup ms-auto mb-0">
                    <a href="{{ route('backend.tag.create') }}" class="btn">{{ __('static.tag.create') }}
                    </a>
                </div>
            @endcan
            @can('backend.tag.destroy')
            <a href="javascript:void(0);" class="btn btn-sm btn-secondary deleteConfirmationBtn"
                style="display: none;" data-url="{{ route('backend.delete.tags') }}">
                <span id="count-selected-rows">0</span> {{__('static.delete_selected')}}
            </a>
            @endcan
        </div>
        </div>
        <div class="card-body common-table">
            <div class="tag-table">
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
