@extends('backend.layouts.master')

@section('title', __('static.page.edit'))

@section('content')
    <div class="row">
        <div class="m-auto col-xl-10 col-xxl-8">
            <div class="card tab2-card">
                <div class="card-header">
                    <h5>{{ __('static.page.edit') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.page.update', $page->id) }}" id="pageForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @includeIf('backend.page.fields')
                        <div class="footer">
                            <button class="btn btn-primary spinner-btn" type="submit">{{ __('static.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection