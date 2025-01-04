@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.campaigns'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.campaigns') }}</li>
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.campaigns')</b></h4>
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
            <div class="col-12 gap-sm-3 gap-2 p-3 mt-5">
                <h5 style="margin-left: 44px;">Add New Campaign</h5>
                <p class="p-3">Campaigns will help you to better promote your marketing strategy. Those are private and individual for each affiliate account.</p>
            </div>
            <div class="col-12 d-flex align-items-center gap-sm-3 gap-2 p-3">
                <div class="col-7 mt-2">
                    <label for="" class="form-label">Campaign Name:</label>
                    <select name="" id="" class="form-control form-control-sm bg-white mb-4"> 
                        <option value="">Cleaning Service campaign</option>
                    </select>
                </div>
                <div class="col-4 mt-2">
                    <button class="btn btn-warning w-100">Generate Link</button>
                </div>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="container table-responsive py-5"> 
                <div class="table-header">
                    <h1>My Campaigns</h1>
                </div>
                <table class="table table-striped table-responsive">
                    <thead class="thead-dark">
                        <tr>
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
                            <td>demotext</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="container table-responsive py-5"> 
                <div class="table-header">
                    <h1>Cleaning Service Campaign</h1>
                </div>
                <table class="table table-striped table-responsive">
                    <thead class="thead-dark">
                        <tr>
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
                            <td>demotext</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="container table-responsive py-5"> 
                <div class="table-header">
                    <h1>Traffic Log</h1>
                </div>
                <table class="table table-striped table-responsive">
                    <thead class="thead-dark">
                        <tr>
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
                            <td>demotext</td>
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