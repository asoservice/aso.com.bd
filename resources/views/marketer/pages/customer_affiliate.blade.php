@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.customer_affiliate'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.customer_affiliate') }}</li>
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.customer_affiliate')</b></h4>
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
                <div class="d-flex gap-3 align-items-end">
                    <div style="width: 100px;height: 1px;background: #dfd4d4;"></div>
                    <h5 style="text-wrap: nowrap;line-height: 16px;">Select Service Zone</h5>
                    <div style="width: 100%;height: 1px;background: #dfd4d4;"></div>
                </div>
            </div>
            <div class="col-12 d-flex gap-2 p-3">
                <div class="col-3 mt-2">
                    <label for="" class="form-label">Country</label>
                    <select name="" id="" class="form-control bg-white"> 
                        <option value="">Bangladesh</option>
                    </select>
                </div>
                <div class="col-3 mt-2">
                    <label for="" class="form-label">Division</label>
                    <select name="" id="" class="form-control bg-white"> 
                        <option value="">Chittagong</option>
                    </select>
                </div>
                <div class="col-3 mt-2">
                    <label for="" class="form-label">District</label>
                    <select name="" id="" class="form-control bg-white"> 
                        <option value="">Feni</option>
                    </select>
                </div>
                <div class="col-3 mt-2">
                    <label for="" class="form-label">Service Zone</label>
                    <select name="" id="" class="form-control bg-white"> 
                        <option value="">Feni Town</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="container table-responsive py-5"> 
                <table class="table table-striped table-responsive">
                    <div class="row gap-2 align-items-center">
                        <div class="col-lg-3 col-12 mt-2 d-flex">
                            <h5>My Affiliate Customers (1,236)</h5>
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
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Signup</th>
                            <th>Order</th>
                            <th>Commission</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Shakibul Hasan Kabir</td>
                            <td>01575454888 <br>emailname@gmail.com</td>
                            <td>17 Dec 2024 <br>12.29 PM</td>
                            <td>1,098 Tk <br>05 Order</td>
                            <td>109.80 Tk</td>
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