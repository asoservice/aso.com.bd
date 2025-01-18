@extends('backend.layouts.master')
@section('title', __('FAQ Categories'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>{{ __('FAQ Categories Management') }}</h5>
        <div class="btn-action">
            <a href="javascript:void(0);" class="btn btn-primary" id="createNewCategory">{{ __('Create New Category') }}</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="content-data-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        @foreach ($labels as $label)
                            <th>{!! $label !!}</th>
                        @endforeach
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="fixed-modal" id="createModalWindow">
    <div class="container">
        <div class="container-child"></div>
    </div>
</div>

<div class="fixed-modal" id="editModalWindow">
    <div class="container">
        <div class="container-child"></div>
    </div>
</div>
@endsection

@push('style')
    <style>
        .fixed-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 99;
            display: none;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .select2-container {
            z-index: 100;
        }
        .fixed-modal .container {
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
        }
        .fixed-modal .container .container-child {
            padding: 20px;
            overflow-y: scroll;
            max-height: 90vh;
        }
        .toast-message {
            text-align: center;
        }
        .sorting_disabled::after,
        .sorting_disabled::before
        {
            display: none !important;  
            content: '' !important;  
        }
    </style>
@endpush

@include('contents.common', ['type' => 'index'])