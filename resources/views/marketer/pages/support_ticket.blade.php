@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.support_ticket'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.support_ticket') }}</li>@lang('static.marketer.support_ticket')
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.provider_affiliate')</b></h4>
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
            <div class="container table-responsive py-5">
                <div class="table-box mt-5">
                    <table class="table table-striped table-responsive">
                        <div class="row gap-2 align-items-center">
                            <div class="col-lg-9 col-8 mt-2">
                                <div class="d-flex gap-3 align-items-end">
                                    <div style="width: 100px;height: 1px;background: #dfd4d4;"></div>
                                    <h5 style="text-wrap: nowrap;line-height: 19px;">Open Ticket (2)</h5>
                                    <div style="width: 100%;height: 1px;background: #dfd4d4;"></div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-2 mt-2">
                                <button class="btn btn-outline-secondary">Create Ticket</button>
                            </div>
                        </div>
                        <thead>
                            <tr class="table-dark">
                                <th>Ticket</th>
                                <th>Conversation</th>
                                <th>Media</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Shakibul Hasan Kabir</td>
                                <td>109.80 Tk</td>
                                <td>
                                    <a href="" class="btn btn-outline-secondary">More Info</a>
                                    <a href="" class="btn btn-outline-secondary">Order History</a>
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