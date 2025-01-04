@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.marketing_guidelines'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.marketing_guidelines') }}</li>
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.marketing_guidelines')</b></h4>
            <div class="btn-action">
                <div class="row">
                    <div class="col-6">
                        <h4 style="color: #009417;"><b>৳ 230.32</b></h4>
                        <span>My Affiliate Earnings (Last 30 days)</span>
                    </div>
                    <div class="col-6">
                        <h4 style="color:rgb(243, 13, 9);"><b>৳ 510.32</b></h4>
                        <span>Affiliate Wallet Balance</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('admin/js/apex-chart.js') }}"></script>

@endpush