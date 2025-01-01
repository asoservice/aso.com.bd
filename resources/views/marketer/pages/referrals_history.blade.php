@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.referrals_history'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.referrals_history') }}</li>
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4>@lang('static.marketer.referrals_history')</h4>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('admin/js/apex-chart.js') }}"></script>

@endpush