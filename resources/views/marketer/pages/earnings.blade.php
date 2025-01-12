@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.earning_payment'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.earning_payment') }}</li>
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.earnings')</b></h4>
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
            <div class="col-12">
                <div class="col-4 mt-2 d-flex" style="float: right;">
                    <img class="active-icon p-2" src="{{ asset('frontend/images/svg/filter.svg') }}">
                    <select name="" id="" class="form-control bg-white"> 
                        <option value="">Lifetime</option>
                    </select>
                </div>
            </div>
            <div class="col-md-8 col-12 mt-5">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <a href="#" class="widget-card" style="background: #E9D4FA;">
                            <div>
                                <h5>৳ 31,210.32</h5>
                                <span>Total Earnings</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-12">
                        <a href="#" class="widget-card" style="background: #CEE9D6;">
                            <div>
                                <h5>৳ 30,600</h5>
                                <span>Total Paid</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-12 mt-2">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <a href="#" class="widget-card">
                            <div>
                                <h5>৳ 100</h5>
                                <span>My Affiliate Earnings</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-12">
                        <a href="#" class="widget-card">
                            <div>
                                <h5>৳ 400</h5>
                                <span>Bonus</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-12 mt-2">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <a href="#" class="widget-card">
                            <div>
                                <h5>৳ 100</h5>
                                <span>Earnings from Marketer</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-12">
                        <a href="#" class="widget-card">
                            <div>
                                <h5>৳ 100</h5>
                                <span>Missing Earnings from Marketer</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="container table-responsive py-5">
                
                <!-- Earnings Summery -->
                <div class="table-box mt-5">
                    <table class="table table-striped table-responsive">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-7 col-12 gap-sm-3 gap-2 mt-5">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 100px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 16px;">Earnings Summery</h5>
                                    <div style="width: 100%;height: 1px;background: #dfd4d4;"></div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mt-2 d-flex gap-2">
                                <img class="active-icon p-2" src="{{ asset('frontend/images/svg/filter.svg') }}">
                                <h6 style="font-size: 15px !important;!i;!;color: #000;margin-top: 11px;">Filter</h6>
                                <select name="" id="" class="form-control bg-white"> 
                                    <option value="">Lifetime</option>
                                </select>
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

                <!-- My Affiliate Earnings -->
                <div class="table-box mt-5">
                    <table class="table table-striped table-responsive">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-7 col-12 gap-sm-3 gap-2 mt-5">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 100px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 16px;">My Affiliate Earnings</h5>
                                    <div style="width: 100%;height: 1px;background: #dfd4d4;"></div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mt-2 d-flex gap-2">
                                <img class="active-icon p-2" src="{{ asset('frontend/images/svg/filter.svg') }}">
                                <h6 style="font-size: 15px !important;!i;!;color: #000;margin-top: 11px;">Filter</h6>
                                <select name="" id="" class="form-control bg-white"> 
                                    <option value="">Lifetime</option>
                                </select>
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
                                <td>Referral Order Commission</td>
                                <td>1,098.00 Tk<br>05</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Customer Order Commission</td>
                                <td>1,098.00 Tk<br>05</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Provider Order Commission</td>
                                <td>1,098.00 Tk<br>05</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">Details</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Referral Order Commission -->
                <div class="table-box mt-5">
                    <table class="table table-striped table-responsive">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-4 col-12">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 110px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 19px;">Referral Order Commission</h5>
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
                                <td>#EA157</td>
                                <td>17 Dec 2024 <br>12.29 PM</td>
                                <td>Iftekhar Hasan Kabir <br>01575454222</td>
                                <td>Iftekhar Hasan Kabir <br>01575454222</td>
                                <td>1,098 Tk</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">More Info</a>
                                    <a href="" class="btn btn-outline-secondary">View Orders</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Customer Order Commission -->
                <div class="table-box mt-5">
                    <table class="table table-striped table-responsive">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-4 col-12">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 110px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 19px;">Customer Order Commission</h5>
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
                                <td>#EA157</td>
                                <td>17 Dec 2024 <br>12.29 PM</td>
                                <td>Iftekhar Hasan Kabir <br>01575454222</td>
                                <td>Iftekhar Hasan Kabir <br>01575454222</td>
                                <td>1,098 Tk</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">More Info</a>
                                    <a href="" class="btn btn-outline-secondary">View Orders</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Provider Order Commission -->
                <div class="table-box mt-5">
                    <table class="table table-striped table-responsive">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-4 col-12">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 110px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 19px;">Provider Order Commission</h5>
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
                                <td>#EA157</td>
                                <td>17 Dec 2024 <br>12.29 PM</td>
                                <td>Iftekhar Hasan Kabir <br>01575454222</td>
                                <td>Iftekhar Hasan Kabir <br>01575454222</td>
                                <td>1,098 Tk</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">More Info</a>
                                    <a href="" class="btn btn-outline-secondary">View Orders</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Earning from 1st Layer Marketer -->
                <div class="table-box mt-5">
                    <table class="table table-striped table-responsive">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-4 col-12">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 110px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 19px;">Earning from 1st Layer Marketer</h5>
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
                                <td>#EA157</td>
                                <td>17 Dec 2024 <br>12.29 PM</td>
                                <td>Iftekhar Hasan Kabir <br>01575454222</td>
                                <td>Iftekhar Hasan Kabir <br>01575454222</td>
                                <td>1,098 Tk</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">More Info</a>
                                    <a href="" class="btn btn-outline-secondary">View Orders</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Earning from 2nd Layer Marketer -->
                <div class="table-box mt-5">
                    <table class="table table-striped table-responsive">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-4 col-12">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 110px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 19px;">Earning from 2nd Layer Marketer</h5>
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
                                <td>#EA157</td>
                                <td>17 Dec 2024 <br>12.29 PM</td>
                                <td>Iftekhar Hasan Kabir <br>01575454222</td>
                                <td>Iftekhar Hasan Kabir <br>01575454222</td>
                                <td>1,098 Tk</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">More Info</a>
                                    <a href="" class="btn btn-outline-secondary">View Orders</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Earning from 3rd Layer Marketer -->
                <div class="table-box mt-5">
                    <table class="table table-striped table-responsive">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-4 col-12">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 110px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 19px;">Earning from 3rd Layer Marketer</h5>
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
                                <td>#EA157</td>
                                <td>17 Dec 2024 <br>12.29 PM</td>
                                <td>Iftekhar Hasan Kabir <br>01575454222</td>
                                <td>Iftekhar Hasan Kabir <br>01575454222</td>
                                <td>1,098 Tk</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">More Info</a>
                                    <a href="" class="btn btn-outline-secondary">View Orders</a>
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