@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@use('App\Traits\Date')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.campaigns'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.campaigns') }}</li>
@endsection


@section('content')

@push('css')
<link href="//cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css" rel="stylesheet">
@endpush

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
            <div class="col-12">
                <form method="post" action="{{ route('campaign.store') }}" class="row">
                    @csrf
                    <div class="col-7 mt-2">
                        <label for="" class="form-label">Campaign Name:</label>
                        <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" id="name" required autocomplete="off">
                        {{-- @error('name')
                            <div class="alert alert-danger">
                                <span>{{ $message }}</span>
                            </div>
                        @enderror --}}
                    </div>
                    <div class="col-4 mt-2" style="padding: 1.9rem">
                        <button class="btn btn-warning w-100">+ Add New Campaign</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="container table-responsive py-5"> 
                <div class="table-box">
                    <table class="table table-striped table-responsive pb-5" id="myTable">
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
                                    <option value="Lifetime">Lifetime</option>
                                    <option value="Today">Today</option>
                                    <option value="Yesterday">Yesterday</option>
                                    <option value="7 Days">Last 7 Days</option>
                                    <option value="15 Days">Last 15 Days</option>
                                    <option value="30 Days">Last 30 Days</option>
                                    <option value="60 Days">Last 60 Days</option>
                                    <option value="180 Days">Last 180 Days</option>
                                    <option value="1 Year">Last 1 Year</option>
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
                                <th>Sl</th>
                                <th>Campaign</th>
                                <th>Created</th>
                                <th>Visits</th>
                                <th>Approved Order</th>
                                <th>Commission</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['my_camp'] as $v)    
                            <tr>
                                <td>{{$data['sl']++}}</td>
                                <td>{{$v->name}}</td>
                                <td>
                                    @php
                                    
                                    $result = App\Traits\Date::explodeDateTime(' ',$v->created_at);
                                    @endphp
                                    {{Date::DbToOriginal('-',$result['date'])}} {{Date::twelveHrTime($result['time'])}}
                                </td>
                                <td>00</td>
                                <td>00</td>
                                <td>00</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="" class="btn btn-outline-secondary">Copy Link</a>
                                        <a href="" class="btn btn-outline-secondary">Performance</a>
                                        <a href="" class="btn btn-outline-secondary">Remove</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                    {{ $data['my_camp']->links() }}
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
                                    <div class="btn-group">
                                        <a href="" class="btn btn-outline-secondary">More Info</a>
                                        <a href="" class="btn btn-outline-secondary">View Order</a>
                                    </div>
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
                                    <option value="Lifetime">Lifetime</option>
                                    <option value="Today">Today</option>
                                    <option value="Yesterday">Yesterday</option>
                                    <option value="7 Days">Last 7 Days</option>
                                    <option value="15 Days">Last 15 Days</option>
                                    <option value="30 Days">Last 30 Days</option>
                                    <option value="60 Days">Last 60 Days</option>
                                    <option value="180 Days">Last 180 Days</option>
                                    <option value="1 Year">Last 1 Year</option>
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
                                    <div class="btn-group">
                                        <a href="" class="btn btn-outline-secondary">Orders</a>
                                    </div>
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
<script src="//cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
<script>
    $('#myTable').dataTable({
        "paging": false,
        "searching": false
    });
</script>
@endpush