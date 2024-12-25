@use('app\Helpers\Helpers')
@extends('frontend.layout.master')
@php
$isCouponEnabled = Helpers::couponIsEnable();
@endphp

@section('title', __('frontend::static.bookings.my_cart'))

@section('breadcrumb')
<nav class="breadcrumb breadcrumb-icon">
    <a class="breadcrumb-item" href="{{url('/')}}">{{__('frontend::static.bookings.home')}}</a>
    <span class="breadcrumb-item active">{{__('frontend::static.bookings.my_cart')}}</span>
</nav>
@endsection

@section('content')
<!-- Service List Section Start -->
<section class="section-b-space service-list-section">
    <div class="container-fluid-lg">
        <div class="row g-3">
            <div class="col-xxl-8 col-xl-7 col-12">
                <div class="cart br-10 br-br-0 br-bl-0">
                    <div class="cart-header">
                        <h3 class="mb-0 f-w-600">{{__('frontend::static.cart.added_items_details')}}</h3>
                        @if(count($cartItems ?? []))
                        <span>{{ count($cartItems ?? []) }} {{__('frontend::static.cart.items_in_cart')}}</span>
                        @endif
                    </div> 
                    <div class="cart-body">
                        <div class="cart-itmes">
                            @forelse ($cartItems as $serviceBooking)
                            @php
                            $isPackageBooking = isset($serviceBooking['service_packages']);
                            $service = $isPackageBooking ? $serviceBooking['service_packages']['services'] :
                            $serviceBooking;
                            $services[] = $service;
                            @endphp
                            @if(isset($serviceBooking['service_id']))
                            @php
                            // Fetch service and provider details
                            $service = Helpers::getServiceById($serviceBooking['service_id']);
                            $provider = Helpers::getProviderById($service?->user_id);
                            @endphp
                            <div class="cart-item">
                                <div class="cart-heading">
                                    <div class="cart-title">
                                        <img src="{{ $provider?->media->first()->getUrl() }}"
                                            alt="{{ $provider?->name }}" class="img-45">
                                        <div>
                                            <a href="{{route('frontend.provider.details', $provider->slug)}}"
                                                target="_blank">
                                                <p class="mb-1">{{ $provider?->name }}</p>
                                            </a>
                                            <div class="rate">
                                                <img src="{{ asset('frontend/images/svg/star.svg') }}" alt="star"
                                                    class="img-fluid star">
                                                <small>{{ $provider?->review_ratings ?? 'Unrated' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart-action">
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#bookServiceModal-{{ $service->id }}">
                                            <i class="iconsax edit" icon-name="edit-2"></i>
                                        </button>
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#deleteCartModel-{{$serviceBooking['service_id']}}">
                                            <i class="iconsax delete" icon-name="trash"></i>
                                        </button>
                                    </div>
                                    @includeIf('frontend.inc.modal', ['service' => $service])
                                </div>
                                <div class="cart-detail">
                                    <div class="selected-service pb-0 border-bottom-0">
                                        <img src="{{ $service?->web_img_thumb_url }}" alt="service"
                                            class="br-10 selected-img">
                                        <div class="service-info">
                                            <div
                                                class="d-flex flex-xxl-row flex-column align-items-xxl-center align-items-start justify-content-between gap-1">
                                                <div class="d-flex align-items-center gap-2">
                                                    <h3>{{ $service?->title }}</h3>
                                                    @if($service?->discount)
                                                    <small class="discount">({{ $service?->discount }}%
                                                        {{__('frontend::static.cart.off')}})</small>
                                                    @endif
                                                </div>
                                                <span
                                                    class="price">{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($service->service_rate) }}</span>
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap gap-2 mt-2">
                                                <p>{{__('frontend::static.bookings.date_time')}}</p>
                                                <ul class="date">
                                                    <li class="d-flex align-items-center gap-1">
                                                        <i class="iconsax" icon-name="calendar-1"></i>
                                                        <span>{{ \Carbon\Carbon::parse($serviceBooking['date_time'])->format('j F, Y') }}</span>
                                                    </li>
                                                    <li class="d-flex align-items-center gap-1">
                                                        <i class="iconsax" icon-name="clock"></i>
                                                        <span>{{ \Carbon\Carbon::parse($serviceBooking['date_time'])->format('g:i A') }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <ul class="date-time pt-3">
                                                <li class="w-100 lh-1">
                                                    <span>{{__('frontend::static.cart.selected_servicemen')}}</span>
                                                    <small
                                                        class="text-primary">{{ $serviceBooking['required_servicemen'] }}
                                                        {{__('frontend::static.cart.servicemen')}}</small>
                                                </li>
                                            </ul>
                                            <div class="dashed-border mt-3"></div>
                                            @if($serviceBooking['select_serviceman'] = 'as_per_my_choice')
                                            @if(!empty($serviceBooking['serviceman_id']))
                                            @php
                                            $servicemenIds = explode(',', $serviceBooking['serviceman_id']);
                                            $servicemen = Helpers::getUsersByIds($servicemenIds ?? []);
                                            @endphp
                                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                                @forelse($servicemen as $serviceman)
                                                <div class="servicemen-list-item">
                                                    <div class="list">
                                                        <img src="{{ $serviceman?->media->first()->getUrl() }}"
                                                            alt="feature" class="img-45">
                                                        <div>
                                                            <p>{{__('frontend::static.cart.servicemen')}}</p>
                                                            <ul>
                                                                <li>
                                                                    <h5>{{ $serviceman?->name }}</h5>
                                                                </li>
                                                                <li>
                                                                    <div class="rate">
                                                                        <img src="{{ asset('frontend/images/svg/star.svg') }}"
                                                                            alt="star" class="img-fluid star">
                                                                        <small>{{ $serviceman?->review_ratings ?? 'Unrated' }}</small>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                @empty
                                                <div class="no-data-found">
                                                    <p>{{__('frontend::static.cart.servicemen_not_found')}}</p>
                                                </div>
                                                @endforelse
                                            </div>
                                            @endif
                                            @elseif($serviceBooking['select_serviceman'] = 'app_choose')
                                            <div class="note m-0">
                                                <p class="mt-1">
                                                    {{__('frontend::static.cart.app_choose_note')}}
                                                </p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @elseif($serviceBooking['service_packages'])
                            @isset($serviceBooking['service_packages']['service_package_id'])
                            @php
                            $id = $serviceBooking['service_packages']['service_package_id'];
                            $servicePackage = Helpers::getServicePackageById($id)
                            @endphp
                            <div class="cart-item">
                                <div class="cart-heading">
                                    <div class="cart-title">
                                        <img src="{{ $servicePackage?->user?->media?->first()?->getUrl() }}"
                                            alt="{{ $servicePackage?->user?->name }}" class="img-45">
                                        <div>
                                            <a href="{{route('frontend.provider.details', $servicePackage?->user?->slug)}}"
                                                target="_blank">
                                                <p class="mb-1">{{ $servicePackage?->user?->name }}</p>
                                            </a>
                                            <div class="rate">
                                                <img src="{{ asset('frontend/images/svg/star.svg') }}" alt="star"
                                                    class="img-fluid star">
                                                <small>{{ $servicePackage?->user?->review_ratings ?? 'Unrated' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart-action">
                                        <a href="{{route('frontend.booking.service-package', $servicePackage?->slug)}}">
                                            <i class="iconsax edit" icon-name="edit-2"></i>
                                        </a>
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#deleteCartModel-{{$servicePackage->id}}">
                                            <i class="iconsax delete" icon-name="trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="cart-detail">
                                    <div class="selected-service">
                                        <img src="{{ asset('frontend/images/svg/service-package.svg') }}" alt="service"
                                            class="br-10 selected-img">
                                        <div class="service-info">
                                            <div
                                                class="d-flex flex-xxl-row flex-column align-items-xxl-center align-items-start justify-content-between gap-1">
                                                <div class="d-flex align-items-center gap-2">
                                                    <h3>{{ $servicePackage?->title }}</h3>
                                                    @if($servicePackage?->discount)
                                                    <small class="discount">({{ $servicePackage?->discount }}%
                                                        {{__('frontend::static.cart.off')}})</small>
                                                    @endif
                                                </div>
                                                @php
                                                $salePrice = Helpers::getServicePackageSalePrice($servicePackage?->id)
                                                @endphp
                                                <span
                                                    class="price">{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($salePrice) }}</span>
                                            </div>
                                            @if( count($serviceBooking['service_packages']['services']))
                                            <ul class="date-time pt-1">
                                                <li class="w-100 lh-1">
                                                    <span>Included services :</span>
                                                    <small
                                                        class="text-primary">{{ count($serviceBooking['service_packages']['services']) }}
                                                        services</small>
                                                </li>
                                            </ul>
                                            @endif
                                            <h5>
                                                {{__('frontend::static.cart.description')}}
                                            </h5>
                                            <p>
                                                {{ $servicePackage?->description }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endisset
                            @endif
                            @empty
                            <div class="no-cart-found no-cart-data text-center">
                                <h3>{{__('frontend::static.cart.nothing_added')}}</h3>
                                <p class="text-light">{{__('frontend::static.cart.nothing_added_note')}}</p>
                                <a href="{{route('frontend.service.index')}}" class="btn btn-solid d-inline-block w-auto mt-4">Explore
                                Services</a>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @if(count(@$cartItems?? []))
            <div class="col-xxl-4 col-xl-5 col-12">
                <div class="position-sticky mb-0">
                    <div class="cart br-10 br-br-0 br-bl-0">
                        <div class="cart-header">
                            <h3 class="mb-0 f-w-600">{{__('frontend::static.cart.payment_summary')}}</h3>
                        </div>

                        <div class="cart-body">
                            @if($isCouponEnabled)
                            @php
                            $isCouponApplied = (session()?->has('coupon') &&
                            $checkout['total']['coupon_total_discount']);
                            @endphp
                            @isset($checkout['total']['coupon_total_discount'])
                            <h5 class="mb-2 d-flex align-items-center justify-content-between">
                                {{__('frontend::static.cart.applied_discount')}}<a href="#couponModal"
                                    data-bs-toggle="modal" class="ms-auto">{{__('frontend::static.cart.view_all')}}</a>
                            </h5>
                            <form id="applyCouponForm" method="POST" action="{{ route('frontend.coupon.handle') }}">
                                @csrf
                                <div class="input-group">
                                    <input type="text" id="couponInput" name="coupon" placeholder="Enter code"
                                        class="form-control form-control-white text-start text-muted {{($isCouponApplied)? 'pattern-input' : ''}}"
                                        value="{{session('coupon', old('coupon'))}}">

                                    @if(!$isCouponApplied)
                                    <button type="submit " class="pattern-btn spinner-btn" id="applyCouponBtn">
                                        {{__('frontend::static.cart.apply')}}
                                        <span class="spinner-border spinner-border-sm text-light" id="applySpinner" style="display:none;"></span> 
                                    </button>
                                    @else
                                      <!-- Remove Coupon Button -->
                                      <button type="submit" id="removeCouponBtn"  name="removeCouponBtn" class="pattern-btn-1 spinner-btn">{{__('frontend::static.cart.remove')}}</button>
                                      <span class="spinner-border spinner-border-sm text-light" id="applySpinner" style="display:none;"></span> 

                                      <input type="hidden" name="remove_coupon" value="1" id="removeCouponField" style="display:none;">
                                    @endif
                                </div>
                                @error('coupon')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </form>
                            <div id="couponMessage"></div>
                            @if($checkout['total']['coupon_total_discount'])
                            <div class="mt-2">
                                <p class="mb-1 d-flex align-items-center gap-1 text-success">
                                    <img src="{{ asset('frontend/images/svg/coupon.svg')}}" alt="" class="img-20">
                                    {{__('frontend::static.cart.hurray_you_saved')}}
                                    {{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($checkout['total']['coupon_total_discount']) }} {{__('frontend::static.cart.with_this_coupon')}}
                                    #{{ session('coupon') }}.
                                </p>
                                <p class="ps-3 text-success">
                                    ({{__('frontend::static.cart.coupon_already_applied_in_subtotal')}})</p>
                            </div>
                            @endif
                            @endisset
                            @endif
                            <div class="bill-summary mt-4">
                                @if($checkout)
                                <ul class="charge">
                                    @isset($checkout['services'])
                                    @foreach($checkout['services'] as $serviceItem)
                                    @php
                                    $service = Helpers::getServiceById($serviceItem['service_id']);
                                    @endphp
                                    <li>
                                        <p>{{ $service?->title }}
                                            <button type="button" class="servoice-info-modal" data-bs-toggle="modal"
                                                data-bs-target="#serviceCharge-{{ $serviceItem['service_id'] }}">
                                                <i class="iconsax" icon-name="info-circle"></i>
                                            </button>
                                        </p>
                                        <span>{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($serviceItem['total']['subtotal']) }}</span>
                                    </li>
                                    @endforeach
                                    @endisset
                                    @isset($checkout['services_package'])
                                    @foreach($checkout['services_package'] as $servicePackageItem)
                                    @php
                                    $servicePackage =
                                    Helpers::getServicePackageById($servicePackageItem['service_package_id']);
                                    $salePrice = Helpers::getServicePackageSalePrice($servicePackage?->id)
                                    @endphp
                                    @if($servicePackage)
                                    <li>
                                        <p>{{ $servicePackage?->title }}
                                            <button type="button" class="servoice-info-modal">
                                            </button>
                                        </p>
                                        <span>{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($salePrice) }}</span>
                                    </li>
                                    @endif
                                    @endforeach
                                    @endisset
                                    <li>
                                        <p>{{__('frontend::static.cart.subtotal')}}</p>
                                        <span>{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($checkout['total']['subtotal']) }}</span>
                                    </li>
                                    <li>
                                        <p>{{__('frontend::static.cart.tax')}}</p>
                                        <span>{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($checkout['total']['tax']) }}</span>
                                    </li>
                                    <li>
                                        <p>{{__('frontend::static.cart.platform_fees')}}</p>
                                        <span>{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($checkout['total']['platform_fees']) }}</span>
                                    </li>
                                    @if($checkout['total']['coupon_total_discount'])
                                    <li>
                                        <p>{{__('frontend::static.cart.coupon_discount')}}</p>
                                        <span
                                            class="text-success">-{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($checkout['total']['coupon_total_discount']) }}</span>
                                    </li>
                                    @endif
                                </ul>
                                <ul class="total">
                                    <li>
                                        <p>{{__('frontend::static.cart.total_amount')}}</p>
                                        @if($checkout['total']['total'])
                                        <span>{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($checkout['total']['total']) }}</span>
                                        @elseif(isset($checkout['total']['total']))
                                        <span>0</span>
                                        @endif
                                    </li>
                                </ul>
                                @else
                                <div class="text-center">
                                    <div class="cart-img my-5">
                                        <img src="{{ asset('frontend/images/cart/1.png')}}" alt="no cart">
                                    </div>
                                    <div class="no-cart-found">
                                        <h3>{{__('frontend::static.cart.nothing_added')}}</h3>
                                        <p class="text-light">{{__('frontend::static.cart.nothing_added_note')}}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @if(count($checkout['services'] ?? []))
                            <div class="dashed-border"></div>
                            <div class="note">
                                <label>{{__('frontend::static.cart.disclaimer')}}</label>
                                <p class="text-danger m-0">{{__('frontend::static.cart.disclaimer_note')}}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @isset($checkout['total']['total'])
                    <div class="view">
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <span>{{__('frontend::static.cart.total')}}</span>
                            <small
                                class="value">{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($checkout['total']['total']) }}</small>
                        </div>
                        <a href="{{route('frontend.payment.index')}}" class="btn btn-solid mt-3">
                            {{__('frontend::static.cart.proceed_to_checkout')}}
                            <i class="iconsax" icon-name="chevron-right"></i>
                        </a>
                    </div>
                    @endisset
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
<!-- Book Service Modal -->

<!-- Coupon modal -->
@if($isCouponEnabled)
<div class="modal fade coupon-modal" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="couponModalLabel">{{__('frontend::static.cart.coupons')}}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="coupon-list custom-scroll">
                    @php
                    $coupons = Helpers::getCoupons();
                    @endphp
                    @forelse($coupons as $coupon)
                    <div class="coupon-item">
                        <div class="coupon-content">
                            <div>
                                <h5>{{__('frontend::static.cart.spend')}} {{$coupon?->min_spend}}
                                    {{__('frontend::static.cart.amount')}}</h5>
                                <p>Use code <span>#{{ $coupon?->code }}</span> spend {{$coupon?->amount}}
                                    {{ ($coupon?->type == 'fixed')?  Helpers::getDefaultCurrencySymbol()  : '%'  }}
                                    {{__('frontend::static.cart.off_real_price')}}</p>
                            </div>
                            <span class="percent">
                                {{$coupon?->amount}}
                                {{ ($coupon?->type == 'fixed')?  Helpers::getDefaultCurrencySymbol()  : '%'  }}
                                {{__('frontend::static.cart.off')}}
                            </span>
                        </div>
                        <div class="circle"></div>
                        <div class="coupon-footer">
                            <p>{{__('frontend::static.cart.valid_till')}}<span>{{ \Carbon\Carbon::parse($coupon?->end_date)->format('j F, Y') }}</span>
                            </p>
                            <!-- Add data-coupon to the 'Use Code' button -->
                            <a href="javascript:void(0)" id="useCode" class="use-code"
                                data-coupon="{{ $coupon?->code }}">
                                {{__('frontend::static.cart.use_code')}}
                                <i class="iconsax" icon-name="arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <p> {{__('frontend::static.cart.coupon_not_found')}}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endif

@isset($checkout['services'])
<!-- service info modal -->
@foreach($checkout['services'] as $serviceCheckout)
@php
$service = Helpers::getServiceById($serviceCheckout['service_id']);
@endphp
<div class="modal fade service-charge-modal" id="serviceCharge-{{$serviceCheckout['service_id']}}" tabindex="-1"
    aria-labelledby="serviceChargeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="serviceChargeLabel">{{$service?->title}}</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bill-summary">
                    <ul class="charge">
                        @php
                        $perServicemenCharge =
                        Helpers::covertDefaultExchangeRate($serviceCheckout['per_serviceman_charge']);
                        $reqServicemen = $checkout['total']['required_servicemen'];
                        $totalAmount = $perServicemenCharge*$reqServicemen;
                        @endphp
                        <li>
                            <p>{{__('frontend::static.cart.per_serviceman_charge')}} </p>
                           {{ Helpers::getDefaultCurrencySymbol() }}{{ $perServicemenCharge }}
                        </li>
                        <li>
                            <p>{{ $checkout['total']['required_servicemen'] }} {{__('frontend::static.cart.servicemen')}}
                               ({{ Helpers::getDefaultCurrencySymbol() }}{{$perServicemenCharge }}*{{ $reqServicemen}})
                            </p>
                           {{ Helpers::getDefaultCurrencySymbol() }}{{ $totalAmount }}
                        </li>
                    </ul>
                    <ul class="total">
                        <li>
                            <p>{{__('frontend::static.cart.total_amount')}}</p>
                           {{ Helpers::getDefaultCurrencySymbol() }}{{$checkout['total']['total_serviceman_charge'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endisset

@foreach ($serviceBookings as $serviceBooking)
<!-- Delete service cart modal -->
<div class="modal fade delete-modal" id="deleteCartModel-{{$serviceBooking['service_id']}}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            <div class="modal-body text-center">
                <i class="iconsax modal-icon" icon-name="trash"></i>
                <h3>Delete Item? </h3>
                <p class="mx-auto">
                {{__('frontend::static.cart.remove_service_from_cart')}}
                </p>
            </div>
            <form action="{{ route('frontend.cart.remove') }}" method="post">
                @method('POST')
                <div class="modal-footer">
                    <input type="hidden" name="service_id" value="{{$serviceBooking['service_id']}}" />
                    <button type="button" class="btn btn-outline"
                        data-bs-dismiss="modal">{{__('frontend::static.cart.no')}}</button>
                    <button type="submit" class="btn btn-solid"
                        data-bs-toggle="modal"
                        data-bs-target="#successfullyDeleteaddressModel">{{__('frontend::static.cart.yes')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('js')
<script>
(function($) {
    "use strict";

    $(document).ready(function() {

        // Form validation setup
        $("#applyCouponForm").validate({
        ignore: [],
        rules: {
            "coupon": {
                required: true, 
            }
        },
        messages: {
            "coupon": {
                required: "Please enter a coupon code" 
            }
        },
        submitHandler: function(form) {
            applyCoupon(form);
        },
    });

    // Trigger form submission for apply coupon (AJAX)
    function applyCoupon(form) {
        
        var formData = $(form).serialize();
        var actionUrl = $(form).attr('action');

        // Show spinner and disable the button
        var $btn = $('#applyCouponBtn');
        var $spinner = $btn.find('.spinner-border');
        $btn.prop('disabled', true).text('');
        $spinner.show();

        // Send AJAX request to handle the coupon
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            success: function(response) {
                // Toggle button visibility based on response
                if (response.status === 'success') {
                    $('#applyCouponBtn').toggle();
                    $('#removeCouponBtn').toggle();
                    location.reload();
                }
            },
            error: function() {
                $('#couponMessage').html('<div class="alert alert-danger">Something went wrong. Please try again.</div>');
                $spinner.hide();
            },
            complete: function() {
                $spinner.hide();
                $btn.text($btn.data('original-text')); // Restore original text
            }
        });

        $spinner.hide();
    }

    // Store original button text for later restoration
    $('#applyCouponBtn').data('original-text', $('#applyCouponBtn').text());

        $('#couponModal').on('click', '.use-code', function() {
            var couponCode = $(this).data('coupon').replace(/^#/, ''); // Remove '#' if it exists
            $('#couponInput').val(couponCode); // Set the coupon code into the input field

            if ($("#applyCouponForm").valid()) {
                $('#applyCouponForm').submit(); // Submit the form
                $('#couponModal').modal('hide'); // Close the modal
            }
        });

    });
})(jQuery);
</script>
@endpush