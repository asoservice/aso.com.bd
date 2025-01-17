@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.payments'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.payments') }}</li>
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <div class="d-flex gap-2 align-items-center">
                <h4><b>@lang('static.marketer.payments')</b></h4>
                    <button class="btn btn-outline-dark">Request Payments</button>
                    <button class="btn btn-outline-dark">Rectangle 266</button>
            </div>
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
            <div class="col-md-8 col-12 mt-5">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <a href="#" class="widget-card">
                            <div>
                                <h5>5.89%</h5>
                                <span>Conversion Rate</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-12">
                        <a href="#" class="widget-card">
                            <div>
                                <h5>31,210</h5>
                                <span>Total Click</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="container table-responsive py-5"> 
                <table class="table table-striped">
                    <div class="row gap-2 align-items-center">
                        <div class="col-lg-3 col-12 mt-2 d-flex">
                            <h5>Generated Affiliate Links</h5>
                        </div>
                        <div class="col-lg-3 col-12 mt-2 d-flex">
                            <img class="active-icon p-2" src="{{ asset('frontend/images/svg/filter.svg') }}">
                            <select name="" id="" class="form-control bg-white"> 
                                <option value="">Lifetime</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-12 mt-2">
                            <select name="" id="" class="form-control bg-white"> 
                                <option value="">Number of items</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-12 mt-2 d-flex">
                            <select name="" id="" class="form-control bg-white"> 
                                <option value="">Search</option>
                            </select>
                            <img class="active-icon p-2" src="{{ asset('frontend/images/svg/search.svg') }}" style="background-color: #00162E;padding: 8px;margin-left: 6px;border-radius: 30%;">
                        </div>
                    </div>
                    <thead>
                        <tr class="table-dark">
                            <th>Affiliate Link</th>
                            <th>Campaign</th>
                            <th>Created</th>
                            <th>Visits</th>
                            <th>Order</th>
                            <th>Conversion</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>https://aso.com.bd/category/cleaning/?ref=1</td>
                            <td>Cleaning Service campaign</td>
                            <td>17 Dec 2024</td>
                            <td>1,25,026</td>
                            <td>1,098 <br>19</td>
                            <td>3.25%</td>
                            <td>
                                <a href="" class="btn btn-outline-secondary">Copy Link</a>
                                <a href="" class="btn btn-outline-secondary">Performance</a>
                                <a href="" class="btn btn-outline-secondary">Remove</a>
                            </td>
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