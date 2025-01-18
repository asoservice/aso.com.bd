@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.comission_rate'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.comission_rate') }}</li>
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.comission_rate')</b></h4>
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
            <div class="col-12 gap-sm-3 gap-2 mt-5">
                
            </div>
        </div>
        <div class="card-body p-3 pt-0">
            <div class="container table-responsive pb-5"> 
                <table class="table table-striped table-responsive">
                    <div class="row gap-2 align-items-center">
                        <div class="col-lg-6 col-12 mt-2">
                            <div class="d-flex gap-3 align-items-end">
                                <div style="width: 100px;height: 1px;background: #dfd4d4;"></div>
                                <h5 style="text-wrap: nowrap;line-height: 16px;">Select Service Zone</h5>
                                <div style="width: 100%;height: 1px;background: #dfd4d4;"></div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-12 mt-2">
                            <select name="" id="" class="form-control bg-white"> 
                                <option value="">Category</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-12 mt-2 d-flex">
                            <input type="search" class="form-control bg-white" placeholder="Search">
                            <img class="active-icon p-2" src="{{ asset('frontend/images/svg/search.svg') }}" style="background-color: #00162E;padding: 8px;margin-left: 6px;border-radius: 30%;">
                        </div>
                    </div>
                    <thead>
                        <tr class="table-dark">
                            <th>Sub-Category</th>
                            <th>Category</th>
                            <th>Commission Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sample sub-category name</td>
                            <td>Sample category 1</td>
                            <td>5%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('admin/js/apex-chart.js') }}"></script>

@endpush