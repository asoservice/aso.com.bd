@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.campaign_reports'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.campaign_reports') }}</li>
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.campaign_reports')</b></h4>
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
        <div class="card-body p-3">
            <div class="container table-responsive py-5">
                <div class="table-box mt-5"> 
                    <table class="table table-striped table-responsive pb-5">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-4 col-12">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 110px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 19px;">My Campaigns</h5>
                                    <div style="width: 40%;height: 1px;background: #dfd4d4;"></div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-12 mt-2 d-flex">
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
                                <input type="search" class="form-control bg-white" placeholder="Search">
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
                                <td>1</td>
                                <td>demotext</td>
                                <td>demotext</td>
                                <td>demotext</td>
                                <td>demotext</td>
                                <td>demotext</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">Orders</a>
                                    <a href="" class="btn btn-outline-secondary">Traffic Log</a>
                                    <a href="" class="btn btn-outline-secondary">Remove</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-box mt-5">
                    <table class="table table-striped table-responsive">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-4 col-12">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 110px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 19px;">Cleaning Service Campaign</h5>
                                    <div style="width: 40%;height: 1px;background: #dfd4d4;"></div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-12 mt-2 d-flex">
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
                                <input type="search" class="form-control bg-white" placeholder="Search">
                                <img class="active-icon p-2" src="{{ asset('frontend/images/svg/search.svg') }}" style="background-color: #00162E;padding: 8px;margin-left: 6px;border-radius: 30%;">
                            </div>
                        </div>
                        <thead>
                            <tr class="table-dark">
                                <th>Order</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Provider</th>
                                <th>Amount</th>
                                <th>Commission</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>demotext</td>
                                <td>demotext</td>
                                <td>demotext</td>
                                <td>demotext</td>
                                <td>demotext</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">More Info</a>
                                    <a href="" class="btn btn-outline-secondary">View Order</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-box mt-5">
                    <table class="table table-striped table-responsive">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-3 col-12 mt-2 d-flex">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 110px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 19px;">Traffic Log</h5>
                                    <div style="width: 40%;height: 1px;background: #dfd4d4;"></div>
                                </div>
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
                                <input type="search" class="form-control bg-white" placeholder="Search">
                                <img class="active-icon p-2" src="{{ asset('frontend/images/svg/search.svg') }}" style="background-color: #00162E;padding: 8px;margin-left: 6px;border-radius: 30%;">
                            </div>
                        </div>
                        <thead>
                            <tr class="table-dark">
                                <th>Device</th>
                                <th>Last Visit</th>
                                <th>Location</th>
                                <th>Visits</th>
                                <th>Order</th>
                                <th>Commission</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>demotext</td>
                                <td>demotext</td>
                                <td>demotext</td>
                                <td>demotext</td>
                                <td>demotext</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">Orders</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('admin/js/apex-chart.js') }}"></script>

@endpush