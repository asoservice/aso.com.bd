@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.service_affiliate_links'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.service_affiliate_links') }}</li>
@endsection

@push('css')
<style>

.hotel .hotel-img {
    width: 100%;
    border-radius: 8px;
}

.hotel .name {
    font-size: 1.2rem;
}

.hotel .name .city {
    font-weight: normal;
    font-size: 0.85rem
}

.hotel .btn {
    width: 150px;
    border-radius: 0
}

.hotel .btn.enquiry {
    border-radius: 5px;
    background: linear-gradient(144deg, #92E92E 7.85%, #009417 103.28%);
    color: #fff;
}

.hotel .btn.enquiry:hover {
    background: linear-gradient(144deg, #92E92E 7.85%, #009417 103.28%);
}

.post_by {
    margin-top: 25px;
}

.post_by img{
    width: 45px;
    border-radius: 50px;
}

.required-time {
    margin-left: 80px;
}

.card-group-item {
    padding: 12px;
    margin: 4px;
}
</style>
@endpush

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.service_affiliate_links')</b></h4>
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
                <h5 style="margin-left: 44px;">Select Service Zone</h5>
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
        <div class="card-body p-3 border-top">
            <div class="container my-sm-5 p-0">
                <div id="content">
                    <div class="d-sm-flex">
                        <div class="col-md-4 py-2 px-2 pb-4">
                            <div class="p-2 bg-light ms-md-4 ms-sm-2 border">
                                <div class="d-flex align-items-center border-bottom p-1">
                                    <h5>Filter</h5>
                                    <a href="#" style="margin-left: 67%;">Clear all</a>
                                </div>
                                <div class="card-group-item pt-3">
                                    <div class="bg-white p-2">
                                        <header>
                                            <span class="title">Sort by </span>
                                        </header>
                                        <form>
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" value="">
                                                <span class="form-check-label">
                                                    Mersedes Benz
                                                </span>
                                            </label>
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" value="">
                                                <span class="form-check-label">
                                                    Nissan Altima
                                                </span>
                                            </label>
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" value="">
                                                <span class="form-check-label">
                                                    Another Brand
                                                </span>
                                            </label>
                                        </form>
                                    </div>
	
                                    <div class="p-2 mt-3">
                                        <header class="bg-white p-2">
                                            <h6 class="title"><img class="active-icon" src="{{ asset('frontend/images/svg/Frame.svg') }}"> Search </h6>
                                        </header>
                                        <form action="" class="mt-2">
                                            <label class="form-check">
                                                <input class="form-check-input" type="radio" name="exampleRadio" value="">
                                                <span class="form-check-label">Robert Davis | 5 Served</span>
                                            </label>
                                            <label class="form-check">
                                                <input class="form-check-input" type="radio" name="exampleRadio" value="">
                                                <span class="form-check-label">Robert Davis | 0 Served</span>
                                            </label>
                                            <label class="form-check">
                                                <input class="form-check-input" type="radio" name="exampleRadio" value="">
                                                <span class="form-check-label">Robert Davis | 3 Served</span>
                                            </label>
                                        </form>
                                    </div>
                                    <div class="p-2 mt-3">
                                        <header class="d-flex">
                                            <span class="title">Categories </span>
                                            <img class="active-icon" src="{{ asset('frontend/images/svg/arrow.svg') }}" width="15px">
                                        </header>
                                        <h6 class="bg-white title mt-3 mb-3"> Search </h6>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 py-2 px-2 pb-4">
                            <div class="bg-white p-2">
                                <div class="hotel">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <img src="https://images.unsplash.com/photo-1580835845971-a393b73bf370?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=967&q=80" alt="" class="hotel-img">
                                            <div class="align-items-md-center d-flex p-2">
                                                <div class="name">Mayflower Hibiscus Inn</div>
                                                <del><span style="color: #999;padding: 10px;">$44.00</span></del><h5>$44.00</h5>
                                            </div>
                                            <div class="d-flex mb-3">
                                                <div class="time">45 minutes</div>
                                                <div class="required-time">Minimum 3 person require</div>
                                            </div>
                                            <p style="text-align: justify;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut vero doloremque minus similique, cum debitis!</p>
                                            <div class="post_by gap-sm-3 gap-2 d-flex">
                                                <img src="https://images.unsplash.com/photo-1580835845971-a393b73bf370?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=967&q=80" alt="">
                                                <div class="names">
                                                    <h5>Tazim</h5>
                                                    <span><i class="fa fa-star"></i>4.7</span>
                                                </div>
                                                <div class="btn enquiry text-uppercase">Copy Link</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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