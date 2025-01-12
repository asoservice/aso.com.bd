@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.setting'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.setting') }}</li>
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.setting')</b></h4>
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
        <div class="card-body p-3 pt-0">
            <div class="container table-responsive pb-5"> 
                <div class="row gap-2 align-items-center">
                    <div class="col-lg-12 col-12">
                        <div class="d-flex gap-3 align-items-end">
                            <div style="width: 100px;height: 1px;background: rgba(173, 173, 173, 0.30);"></div>
                            <h5 style="text-wrap: nowrap;line-height: 16px;"><b>Default Payment Method</b></h5>
                            <div style="width: 100%;height: 1px;background: rgba(173, 173, 173, 0.30);"></div>
                        </div>
                    </div>
                    <div class="col-12 d-flex gap-2 p-3">
                        <div class="col-3">
                            <select name="" id="" class="form-control bg-white"> 
                                <option value="">Bank</option>
                            </select>
                        </div>
                        <button class="btn btn-outline-secondary">Save</button>
                    </div>
                </div>
                <div class="row mt-3 p-3">
                    <div class="col-lg-12 col-12">
                        <div class="d-flex gap-3 align-items-end">
                            <div style="width: 100px;height: 1px;background: rgba(173, 173, 173, 0.30);"></div>
                            <h5 style="text-wrap: nowrap;line-height: 16px;"><b>Bank Account</b></h5>
                            <div style="width: 100%;height: 1px;background: rgba(173, 173, 173, 0.30);"></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 p-3">
                        <span>Bank Name: ISLAMI BANK BANGLADESH LTD</span><br>
                        <span>Branch Name: College Road, Feni</span><br>
                        <span>Account Name: Nazmul Haque</span><br>
                        <span>Account No: 20503396700265700</span><br><br>
                        <a href="#edit" class="btn btn-outline-secondary" style="width: 60px;font-size: 12px;">Edit</a>
                    </div>
                    <div class="col-md-6 col-12 p-3">
                        <form action="" method="post">
                            <div class="col-12">
                                <label for="">Select Bank</label>
                                <select name="" id="" class="form-control bg-white"> 
                                    <option value="">Select your bank</option>
                                </select>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="">Branch</label>
                                <input type="text" class="form-control bg-white" placeholder="Branch Name">
                            </div>
                            <div class="col-12 mt-2">
                                <label for="">Account Number</label>
                                <input type="text" class="form-control bg-white" placeholder="Bank Account Number">
                            </div><br>
                            <div class="col-3">
                                <button type="submit" class="btn btn-success" style="background: var(--green-gradiant, linear-gradient(144deg, #92E92E 7.85%, #009417 103.28%));">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-5 p-3">
                    <div class="col-lg-12 col-12">
                        <div class="d-flex gap-3 align-items-end">
                            <div style="width: 100px;height: 1px;background: rgba(173, 173, 173, 0.30);"></div>
                            <h5 style="text-wrap: nowrap;line-height: 16px;"><b>Mobile Banking</b></h5>
                            <div style="width: 100%;height: 1px;background: rgba(173, 173, 173, 0.30);"></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 p-3">
                        <span>Bkash: 01575454111</span><br>
                        <span>Nagad: 01575454111</span>
                        <a href="#edit" class="btn btn-outline-secondary mt-3" style="width: 60px;font-size: 12px;">Edit</a>
                    </div>
                    <div class="col-md-6 col-12 p-3">
                        <form action="" method="post">
                            <div class="col-12">
                                <label for="">Bkash (Personal Number)</label>
                                <input type="text" class="form-control bg-white" placeholder="Bkash Number">
                            </div>
                            <div class="col-12 mt-2">
                                <label for="">Nagad (Personal Number)</label>
                                <input type="text" class="form-control bg-white" placeholder="Nagad Number">
                            </div><br>
                            <div class="col-3">
                                <button type="submit" class="btn btn-success" style="background: var(--green-gradiant, linear-gradient(144deg, #92E92E 7.85%, #009417 103.28%));">Update</button>
                            </div>
                        </form>
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