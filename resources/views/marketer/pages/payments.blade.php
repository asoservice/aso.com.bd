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
                                <h5>৳ 31,210.32</h5>
                                <span>Total Paid</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-12">
                        <a href="#" class="widget-card">
                            <div>
                                <h5>৳ 510.32</h5>
                                <span>Affiliate Balance</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="container table-responsive py-5">
                <div class="table-box">
                    <table class="table table-striped">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-3 col-12 mt-2 d-flex">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 110px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 19px;">Payments History</h5>
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
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment Note</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>17 Dec 2024<br>12.29 PM</td>
                                <td>#aso- 012548</td>
                                <td>1,098 Tk</td>
                                <td><span class="btn btn-warning btn-sm">Processing</span></td>
                                <td></td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>17 Dec 2024<br>12.29 PM</td>
                                <td>#aso- 012548</td>
                                <td>1,098 Tk</td>
                                <td><span class="btn btn-success btn-sm">Paid</span></td>
                                <td>Admin note goes here<br>Admin note goes here</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">View</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-box mt-5">
                    <table class="table table-striped table-responsive">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-12 col-12 gap-sm-3 gap-2 mt-5">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 100px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 16px;">Cleared Affiliate Earnings</h5>
                                    <div style="width: 100%;height: 1px;background: #dfd4d4;"></div>
                                </div>
                            </div>
                        </div>
                        <thead>
                            <tr class="table-dark">
                                <th>Title</th>
                                <th>Order</th>
                                <th>Commission</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>My Affiliate Earnings</td>
                                <td>1,098.00 Tk<br>05</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Bonus</td>
                                <td></td>
                                <td>109.80 Tk</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Earnings from 1st Layer Marketer</td>
                                <td>1,098.00 Tk<br>05</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Earnings from 2nd Layer Marketer</td>
                                <td>1,098.00 Tk<br>05</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Earnings from 3rd Layer Marketer</td>
                                <td>1,098.00 Tk<br>05</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">Details</a>
                                </td>
                            </tr>
                            <tr style="background: #CEE9D6;">
                                <td><b>Total Affiliate Earnings</b></td>
                                <td><b>1,098.00 Tk</b><br>05</td>
                                <td><b>109.80 Tk</b></td>
                                <td>
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