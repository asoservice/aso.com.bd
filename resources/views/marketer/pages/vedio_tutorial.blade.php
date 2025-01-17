@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.vedio_tutorial'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.vedio_tutorial') }}</li>
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.vedio_tutorial')</b></h4>
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
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="align-items-end mt-5">
                            <div class="d-flex gap-3">
                                <select name="" id="" class="form-control bg-white"> 
                                    <option value="">Search</option>
                                </select>
                                <img class="active-icon p-2" src="{{ asset('frontend/images/svg/search.svg') }}" style="background-color: #00162E;padding: 8px;margin-left: 6px;border-radius: 30%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-3 pt-0">
            <div class="container p-5"> 
                <div class="row">
                    <div class="col-md-6 col-12 p-3">
                        <iframe width="450" height="345" src="https://www.youtube.com/embed/tgbNymZ7vqY"></iframe>
                    </div>
                    <div class="col-md-6 col-12 p-3">
                        <iframe width="450" height="345" src="https://www.youtube.com/embed/tgbNymZ7vqY"></iframe>
                    </div>
                    <div class="col-md-6 col-12 p-3">
                        <iframe width="450" height="345" src="https://www.youtube.com/embed/tgbNymZ7vqY"></iframe>
                    </div>
                    <div class="col-md-6 col-12 p-3">
                        <iframe width="450" height="345" src="https://www.youtube.com/embed/tgbNymZ7vqY"></iframe>
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