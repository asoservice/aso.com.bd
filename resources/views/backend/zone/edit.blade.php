@extends('backend.layouts.master')

@section('title', __('static.zone.edit'))

@section('content')
    <div class="row">
    <div class="m-auto col-12-8">
            <div class="card tab2-card">
                <div class="card-header">
                    <h5>{{ __('static.zone.edit') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.zone.update', $zone->id) }}" id="zoneForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @includeIf('backend.zone.fields')
                        <div class="footer">
                            <button id='submitBtn' type="submit" class="btn btn-primary spinner-btn">{{ __('static.submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

