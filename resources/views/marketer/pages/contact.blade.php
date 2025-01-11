@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.marketer.contact'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.marketer.contact') }}</li>
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="card">
        <div class="card-header">
            <h4><b>@lang('static.marketer.contact')</b></h4>
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
                <div class="row">
                    <div class="col-md-6 col-12 p-3">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-6">
                                    <label for="">First Name</label>
                                    <input type="text" class="form-control bg-white" name="" id="" placeholder="First Name">
                                </div>
                                <div class="col-6">
                                    <label for="">Last Name</label>
                                    <input type="text" class="form-control bg-white" name="" id="" placeholder="Last Name">
                                </div>
                                <div class="col-12 mt-2">
                                    <label for="">Email Address</label>
                                    <input type="text" class="form-control bg-white" name="" id="" placeholder="Email Address">
                                </div>
                                <div class="col-12 mt-2 mb-3">
                                    <label for="">Message</label>
                                    <textarea name="" id="" class="form-control bg-white" placeholder="Write your message"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success" style="background: var(--green-gradiant, linear-gradient(144deg,rgb(162, 131, 234) 7.85%,rgb(17, 0, 148) 103.28%));">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 col-12 p-3">
                        <h3 style="color: #000;margin-bottom: 8px;">GET IN TOUCH</h3>
                        <p style="font-size: 15px;line-height: 1.3;text-align: justify;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quia, totam ratione ab saepe eum corporis hic, voluptatibus molestiae atque consequuntur earum modi autem ea ipsa aspernatur beatae minus et blanditiis a similique eaque, explicabo dicta fuga facilis! Quam, mollitia eligendi aliquid harum nam amet facilis a molestias consectetur, odio odit?</p><br>

                        <div class="d-flex gap-4 align-items-center">
                            <img class="active-icon" src="{{ asset('admin/images/svg/mail.svg') }}"> 
                            <span>Email <br> <p>email@gmail.com</p></span>
                        </div>
                        <div class="d-flex gap-4 mt-3 align-items-center">
                            <img class="active-icon" src="{{ asset('admin/images/svg/phone.svg') }}"> 
                            <span>Phone <br><p>01888888888</p></span>
                        </div>
                        <div class="d-flex gap-4 mt-3 align-items-center">
                            <img class="active-icon" src="{{ asset('admin/images/svg/map-pin.svg') }}"> 
                            <span>Location <br><p>Block A, Road No. 5, Uttara, Dhaka</p></span>
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