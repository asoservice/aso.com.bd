@use('App\Models\Booking')
@use('App\Models\CommissionHistory')
@use('app\Helpers\Helpers')
@use('App\Enums\BookingEnum')
@use('App\Enums\BookingEnumSlug')
@extends('marketer.layouts.master')
@section('title', __('static.dashboard.dashboard'))
@section('breadcrumbs')
<li class="breadcrumb-item active">{{ __('static.dashboard.dashboard') }}</li>
@endsection

@section('content')
<div class="row g-sm-4 g-3">
    <div class="col-xxl-4 col-xl-5">
        <div class="row g-sm-4 g-3">
            <div class="col-12">
                <a href="javascript:void(0)" class="widget-card card">
                    <div>
                        
                        @role('admin')
                        <h3>{{ Helpers::getDefaultCurrencySymbol() }}{{ array_sum($data['revenues'] ?? []) }}</h3>
                        @elserole('provider')
                        @php
                        $providerId = Helpers::getCurrentProviderId();
                        $providerCommission = CommissionHistory::where('provider_id', $providerId)->sum(
                        'provider_commission',
                        );
                        @endphp
                        <h3>{{ Helpers::getDefaultCurrencySymbol() }}{{ $providerCommission ?? 0 }}</h3>
                        @endrole
                        <h5>{{ __('static.dashboard.total_revenue') }}</h5>
                    </div>
                    <div class="widget-icon">
                        <i data-feather="credit-card"></i>
                    </div>
                </a>
            </div>
            @unlessrole(['provider', 'serviceman'])
            @can('backend.provider.index')
            <div class="col-xxl-6 col-xl-12 col-sm-6 col-12">
                <a href="{{ route('backend.provider.index') }}" class="widget-card card">
                    <div>
                        <h3>{{ Helpers::getProvidersCount() }}</h3>
                        <h5>{{ __('static.dashboard.total_providers') }}</h5>
                    </div>
                    <div class="widget-icon">
                        <i data-feather="user-plus"></i>
                    </div>
                </a>
            </div>
            @endcan
            @else
            @unlessrole('serviceman')
            @can('backend.serviceman.index')
            <div class="col-xxl-6 col-xl-12 col-sm-6 col-12">
                <a href="{{ route('backend.serviceman.index') }}" class="widget-card card">
                    <div>
                        <h3>{{ Helpers::getServicemenCount() }}</h3>
                        <h5>{{ __('static.dashboard.total_servicemen') }}</h5>
                    </div>
                    <div class="widget-icon">
                        <i data-feather="user-plus"></i>
                    </div>
                </a>
            </div>
            @endcan
            @endunlessrole
            @endunlessrole
            @unlessrole('serviceman')
            @can('backend.service.index')
            <div class="col-sm-6">
                <a href="{{ route('backend.service.index') }}" class="widget-card card">
                    <div>
                        <h3>{{ Helpers::getServicesCount() }}</h3>
                        <h5>{{ __('static.dashboard.total_services') }}</h5>
                    </div>
                    <div class="widget-icon">
                        <i data-feather="settings"></i>
                    </div>
                </a>
            </div>
            @endcan
            @endunlessrole
            @can('backend.booking.index')
            <div class="col-sm-6">
                <a href="{{ route('backend.booking.index') }}" class="widget-card card">
                    <div>
                        <h3>{{ Helpers::getBookingsCount() }}</h3>
                        <h5>{{ __('static.dashboard.total_bookings') }}</h5>
                    </div>
                    <div class="widget-icon">
                        <i data-feather="calendar"></i>
                    </div>
                </a>
            </div>
            @endcan
            @unlessrole(['provider', 'serviceman'])
            @can('backend.customer.index')
            <div class="col-sm-6">
                <a href="{{ route('backend.customer.index') }}" class="widget-card card">
                    <div>
                        <h3>{{ Helpers::getCustomersCount() }}</h3>
                        <h5>{{ __('static.dashboard.total_customers') }}</h5>
                    </div>
                    <div class="widget-icon">
                        <i data-feather="users"></i>
                    </div>
                </a>
            </div>
            @endcan
            @else
            @can('backend.provider_wallet.index')
            <div class="col-sm-6">
                <a href="{{ route('backend.provider-wallet.index') }}" class="widget-card card">
                    <div>
                        <h3>{{ Helpers::getDefaultCurrencySymbol() }}{{ isset(auth()->user()->providerWallet) ? auth()->user()->providerWallet->balance : 0.0 }}
                        </h3>
                        <h5>{{ __('static.dashboard.wallet_balance') }}</h5>
                    </div>
                    <div class="widget-icon">
                        <i data-feather="credit-card"></i>
                    </div>
                </a>
            </div>
            @endcan
            @if (Auth::user()->hasRole('serviceman'))
            <div class="col-sm-6">
                <a href="{{ route('backend.serviceman-wallet.index') }}" class="widget-card card">
                    <div>
                        <h3>{{ Helpers::getDefaultCurrencySymbol() }}{{ isset(auth()->user()->servicemanWallet) ? auth()->user()->servicemanWallet->balance : 0.0 }}
                        </h3>
                        <h5>{{ __('static.dashboard.wallet_balance') }}</h5>
                    </div>
                    <div class="widget-icon">
                        <i data-feather="credit-card"></i>
                    </div>
                </a>
            </div>
            @endif
            @endunlessrole
        </div>
    </div>
    @can('backend.booking.index')
    <div class="col-xxl-8 col-xl-7">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('static.booking.booking_status') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-sm-4 g-3 booking-status-main">
                    
                    
                </div>
            </div>
        </div>
    </div>
    @endcan
    <div class="@if (Auth::user()->hasRole('serviceman')) col-xxl-6 @else col-xxl-7 @endif col-12">
        <div class="card h-100">
            <div class="card-header">
                <h5>{{ __('static.dashboard.average_revenue') }}</h5>
            </div>
            <div class="card-body">
                <div id="basic-apex"></div>
            </div>
        </div>
    </div>
    @unlessrole(['provider', 'serviceman'])
    @can('backend.provider.index')
    <div class="col-xxl-5 col-xl-6 col-12">
        <div class="card h-100 top-provider">
            <div class="card-header">
                <h5>{{ __('static.dashboard.top_providers') }}</h5>
                <a href="{{ route('backend.provider.index') }}" class="view-all">
                    {{ __('static.dashboard.view_all') }}
                    <i data-feather="arrow-right"></i>
                </a>
            </div>
            
        </div>
    </div>
    @endcan
    @else
    @unlessrole('serviceman')
    @can('backend.serviceman.index')
    <div class="col-xxl-5 col-xl-6 col-12">
        <div class="card h-100 top-provider">
            <div class="card-header">
                <h5>{{ __('static.dashboard.top_servicemen') }}</h5>
                <a href="{{ route('backend.serviceman.index') }}" class="view-all">
                    {{ __('static.dashboard.view_all') }}
                    <i data-feather="arrow-right"></i>
                </a>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive provider-box custom-scrollbar">
                    <table class="table">
                        <tbody>
                            @forelse ($topServicemen as $servicemen)
                            <tr>
                                <td>
                                    <div class="provider-detail">
                                        <img class="provider-img"
                                            src="{{ $servicemen?->media?->first()?->getUrl() ?? asset('admin/images/avatar/1.png') }}">
                                        <div class="text-start">
                                            <h5>{{ $servicemen->name }}</h5>
                                            <div class="location">
                                                <i data-feather="map-pin"></i>
                                                <h6>{{ $servicemen->getPrimaryAddressAttribute()->state->name ?? null }}-{{ $servicemen->getPrimaryAddressAttribute()->country->name ?? null }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @isset($servicemen->ServicemanReviewRatings)
                                    <div class="rate">
                                        @for ($i = 0; $i < Helpers::getServicemanReviewRatings($servicemen); ++$i) <img
                                            src="{{ asset('admin/images/svg/star.svg') }}" alt="star"
                                            class="img-fluid star">
                                            @endfor
                                            <small>({{ $servicemen->ServicemanReviewRatings }})</small>
                                    </div>
                                    @endisset
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td>
                                    <div class="table-no-data">
                                        <h4>{{ __('static.data_not_found') }}</h4>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endcan
    @endunlessrole
    @endunlessrole
    @can('backend.booking.index')
    <div class="col-xl-6 col-12">
        <div class="card h-100">
            <div class="card-header">
                <h5>{{ __('static.dashboard.recent_booking') }}</h5>
                <a href="{{ route('backend.booking.index') }}" class="view-all">
                    {{ __('static.dashboard.view_all') }}
                    <i data-feather="arrow-right"></i>
                </a>
            </div>
            <div class="card-body pb-0">
                <div class="table-responsive booking-box custom-scrollbar">
                    <table class="table">
                        <tbody>
                            <thead>
                                <tr>
                                    <th>
                                        Booking
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        View
                                    </th>
                                </tr>
                            </thead>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endcan
    @unlessrole('serviceman')
    @can('backend.service.index')
    <div class="col-xl-6 col-12">
        <div class="card h-100 top-services">
            <div class="card-header">
                <h5>{{ __('static.dashboard.top_services') }}</h5>
                <a href="{{ route('backend.service.index') }}" class="view-all">
                    {{ __('static.dashboard.view_all') }}
                    <i data-feather="arrow-right"></i>
                </a>
            </div>
            
        </div>
    </div>
    @endcan
    @can('backend.review.index')
    <div class="col-xl-6 col-12">
        <div class="card h-100 latest-reviews">
            <div class="card-header">
                <h5>{{ __('static.dashboard.latest_reviews') }}</h5>
                <a href="{{ route('backend.review.index') }}" class="view-all">
                    {{ __('static.dashboard.view_all') }}
                    <i data-feather="arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive review-box custom-scrollbar">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Customer</th>
                                <th>Ratings</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endcan
    @endunlessrole
    @unlessrole(['provider', 'serviceman'])
    @can('backend.blog.index')
    <div class="col-xxl-6 col-12">
        <div class="card h-100 latest-blogs">
            <div class="card-header">
                <h5>{{ __('static.dashboard.latest_blog') }}</h5>
                <a href="{{ route('backend.blog.index') }}" class="view-all">
                    {{ __('static.dashboard.view_all') }}
                    <i data-feather="arrow-right"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="row g-sm-4 g-3 h-100">
                    
                </div>
            </div>
        </div>
    </div>
    @endcan
    @endunlessrole
</div>
@endsection
@push('js')
<script src="{{ asset('admin/js/apex-chart.js') }}"></script>

@endpush