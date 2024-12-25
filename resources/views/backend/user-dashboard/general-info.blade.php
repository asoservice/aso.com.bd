@use('App\Models\Booking')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@use('App\Enums\RoleEnum')

@php
    $role = Helpers::getRoleByUserId(request()->id);
@endphp

@extends('backend.layouts.master')

@section('title', __('static.user_dashboard.general_info'))

@section('content')

<div class="card-body bg-white user-details-dashboard">
    <div class="row">
        <div class="m-auto col-12-8">
            <div class="card tab2-card">
            
                    @includeIf('backend.user-dashboard.index')
                    <div class="card-body">
                        <div class="row g-sm-4 g-3">
                            <div class="col-md-5 col-sm-6">
                                <div class="row g-sm-4 g-3">
                                    <div class="col-xl-6 col-12">
                                        <a href="#!" class="widget-card-2 card bg-light-primary text-primary">
                                            <div class="widget-icon">
                                                <i data-feather="calendar"></i>
                                            </div>
                                            <div>
                                                <h5>{{ __('static.user_dashboard.total_bookings') }}</h5>
                                                <h3>{{ Helpers::getBookingsCountById($user->id) }}</h3>
                                            </div>
                                        </a>
                                    </div>

                                    @if ($role == RoleEnum::PROVIDER)
                                        <div class="col-xl-6 col-12">
                                            <a href="" class="widget-card-2 card bg-light-success text-success">
                                                <div class="widget-icon">
                                                    <i data-feather="user-plus"></i>
                                                </div>
                                                <div>
                                                    <h5>{{ __('static.user_dashboard.total_servicemen') }}</h5>
                                                    <h3>{{ Helpers::getServicemenCountById($user->id) }}</h3>
                                                </div>
                                            </a>
                                        </div>
                                    @endif

                                    @if ($role == RoleEnum::PROVIDER)
                                        <div class="col-xl-6 col-12">
                                            <a href="" class="widget-card-2 card bg-light-info text-info">
                                                <div class="widget-icon">
                                                    <i data-feather="settings"></i>
                                                </div>
                                                <div>
                                                    <h5>{{ __('static.user_dashboard.total_services') }}</h5>
                                                    <h3>{{ Helpers::getServicesCountById($user->id) }}</h3>
                                                </div>
                                            </a>
                                        </div>
                                    @endif

                                    <div class="col-xl-6 col-12">
                                        <a href="" class="widget-card-2 card bg-light-warning text-warning">
                                            <div class="widget-icon">
                                                <i data-feather="credit-card"></i>
                                            </div>
                                            <div>
                                                <h5>{{ __('static.user_dashboard.wallet_balance') }}</h5>
                                                <h3>{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::getBalanceById($user->id) }}
                                                </h3>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7 col-sm-6">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="row g-sm-4 g-3 booking-status-main">
                                            @foreach ([BookingEnum::PENDING => 'box', BookingEnum::ON_GOING => 'calendar', BookingEnum::ON_THE_WAY => 'package', BookingEnum::COMPLETED => 'truck', BookingEnum::CANCEL => 'x-circle', BookingEnum::ON_HOLD => 'alert-circle'] as $status => $icon)
                                                <div class="col-xxl-4 col-md-6 booking-status-card">
                                                    <a href="" class="booking-widget-card card">
                                                        <div>
                                                            <h3>{{ Booking::getBookingStatusById($bookings, $user->id, $status) }}
                                                            </h3>

                                                            <h5>{{ __('static.user_dashboard.' . strtolower(str_replace(' ', '_', $status))) }}
                                                            </h5>
                                                        </div>
                                                        <div class="booking-widget-icon">
                                                            <i data-feather="{{ $icon }}"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
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
