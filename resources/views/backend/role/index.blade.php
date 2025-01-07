@extends('backend.layouts.master')

@section('title', __('static.roles.roles'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="form-group row">
            <form method="post" class="row" action="{{ route('backend.create_permissions') }}">
                @csrf
                <div class="col-lg-4">
                    <input type="text" class="form-control" name="route" id="route" placeholder="Route Name">
                </div>
                <div class="col-lg-4">
                    <button class="btn btn-sm btn-success">Create Permission</button>
                </div>
            </form>
        </div>
        <div class="col-3">
            <a href="{{route('backend.sync_permission')}}" class="btn btn-sm btn-info">Sync Permission</a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header d-flex align-items-center">
        <h5>{{ __('static.roles.roles') }}</h5>
        <div class="btn-action">
            @can('backend.role.create')
            <div class="btn-popup ms-auto mb-0">
                <a href="{{ route('backend.role.create') }}" class="btn">{{ __('static.roles.create') }}
                </a>
            </div>
            @endcan
            @can('backend.role.destroy')
            <a href="javascript:void(0);" class="btn btn-sm btn-secondary deleteConfirmationBtn"
                style="display: none;" data-url="{{ route('backend.delete.roles') }}">
                <span id="count-selected-rows">0</span> {{__('static.delete_selected')}}
            </a>
            @endcan
        </div>
    </div>
    <div class="card-body common-table">
        <div class="role-table">
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

