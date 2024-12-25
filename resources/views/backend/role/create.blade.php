@extends('backend.layouts.master')
@section('title', __('static.roles.create'))
@section('content')
<div class="row">
    <div class="m-auto col-xl-10 col-xxl-8">
        <div class="card tab2-card">
            <div class="card-body">
                <form id="role-form" action="{{ route('backend.role.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('backend.role.fields')
                    <div class="footer">
                        <button id='submitBtn' type="submit" class="btn btn-primary spinner-btn">{{ __('static.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
