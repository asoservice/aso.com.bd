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

<div class="fixed-modal" id="deleteModalWindow">
    <div class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-start">
                    <div class="main-img">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </div>
                    <div class="text-center">
                        <div class="modal-title"> Are you sure want to delete ?</div>
                        <p>This Item Will Be Deleted Permanently. You Can not Undo This Action.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn cancel multi-delete-cancel" id="cancel-delete" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary delete spinner-btn" id="confirm-delete">Delete</button>
                </div>
            </div>
        </div>
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
            text-wrap: nowrap;
        }
        .sorting_disabled::after,
        .sorting_disabled::before
        {
            display: none !important;  
            content: '' !important;  
        }

        .btn {
            opacity: 0.8;
            transition-duration: 500ms;
        }
        .btn:hover {
            opacity: 1;
            letter-spacing: 1px;
        }
        .capitalize {
            text-transform: capitalize;
        }
    </style>
@endpush

@include('contents.common', ['type' => 'index'])