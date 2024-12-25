@use('App\Enums\BookingEnumSlug')
@use('app\Helpers\Helpers')

@extends('frontend.layout.master')

@php
$defaultSymbol = Helpers::getDefaultCurrencySymbol();
@endphp

@section('title', __('frontend::static.bookings.bookings'))

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/vendors/flatpickr/flatpickr.min.css') }}">
@endpush


@section('breadcrumb')
<nav class="breadcrumb breadcrumb-icon">
    <a class="breadcrumb-item" href="{{ route('frontend.home') }}">{{ __('frontend::static.bookings.home') }}</a>
    <span class="breadcrumb-item active">{{ __('frontend::static.bookings.bookings') }}</span>
</nav>
@endsection

@section('content')
<!-- Service List Section Start -->
<section class="section-b-space">
    <div class="container-fluid-lg booking-sec">
        <div class="row g-4">
            <div class="col-custom-3 filter-sidebar">
                <div class="filter sticky booking-category">
                    <div class="card">
                        <div class="card-header">
                            <i class="iconsax close-btn filter-close d-xl-none d-flex" icon-name="arrow-left"></i>
                            <h3>{{ __('frontend::static.filter') }}</h3>
                            <a href="javascript:void(0)" id="clear-all"
                                class="ms-auto">{{ __('frontend::static.bookings.clear_all') }}</a>
                        </div>
                        <form action="{{ route('frontend.booking.index') }}" method="GET">
                            <div class="card-body booking-category-body">
                                <div class="accordion" id="category">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="categoryItem">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapsecategory" aria-expanded="true"
                                                aria-controls="collapsecategory">
                                                {{ __('frontend::static.bookings.category') }}
                                            </button>
                                        </h2>
                                        <div id="collapsecategory" class="accordion-collapse collapse show"
                                            aria-labelledby="collapsecategory" data-bs-parent="#category">
                                            <div class="accordion-body">
                                                <div class="search-div">
                                                    <input type="search" autocomplete="off" class="form-control form-control-white"
                                                        id="accordion_search_bar" placeholder="Search" />
                                                </div>
                                                <input type="hidden" name="categories" id="select-category"
                                                    class="form-check-input" value="">
                                                <p id="no-results-message" class="no-results mt-3"
                                                    style="display: none;">
                                                    Category not found</p>
                                                <div class="category-body">
                                                    <ul class="category-list custom-scroll">
                                                        @forelse($categories as $category)
                                                        <li class="form-check category-item ps-0 pe-2">
                                                            <label class="form-check-label">
                                                                <img src="{{ Helpers::isFileExistsFromURL($category?->media?->first()?->getUrl(), true) }}"
                                                                    alt="">
                                                                <span class="name">{{ $category?->title }}</span>
                                                            </label>
                                                            <input type="checkbox" class="form-check-input"
                                                                value="{{ $category?->slug }}">
                                                            </input>
                                                        </li>
                                                        @empty
                                                        <li class="form-check category-item no-category">
                                                            {{ __('frontend::static.bookings.category_not_found') }}
                                                        </li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion" id="date">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="dateItem">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapsedate" aria-expanded="true"
                                                aria-controls="collapsedate">
                                                {{ __('frontend::static.bookings.date') }}
                                            </button>
                                        </h2>
                                        <div id="collapsedate" class="accordion-collapse collapse show"
                                            aria-labelledby="collapsedate" data-bs-parent="#date">
                                            <div class="accordion-body">
                                                <div class="input-group flatpicker-calender">
                                                    <input class="form-control form-control-white"
                                                        placeholder="Start date" id="datetime-local" type="text"
                                                        readonly="readonly" name="start_date"
                                                        value="{{ request()->start_date }}">
                                                    <i class="iconsax input-icon" icon-name="calendar-1"></i>
                                                </div>
                                                <div class="input-group flatpicker-calender mt-3">
                                                    <input class="form-control form-control-white"
                                                        placeholder="End date" id="datetime-local" type="text"
                                                        readonly="readonly" name="end_date"
                                                        value="{{ request()->end_date }}">
                                                    <i class="iconsax input-icon" icon-name="calendar-1"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="status" value="{{ request()->status }}">
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-outline">
                                    {{ __('frontend::static.bookings.apply_filter') }} </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-custom-9">
                @if (count($bookings ?? []))
                <div class="select-dropdown">
                    @php
                    $bookingStatus = Helpers::getActiveBookingStatusList() ?? [];
                    @endphp
                    <div class="filter-div">
                        <div class="d-xl-none d-block">
                            <a href="javascript:void(0)" class="btn btn-solid filter-btn w-max">
                                {{ __('frontend::static.bookings.filter') }}
                            </a>
                        </div>
                        <h4>
                            {{ __('frontend::static.bookings.all_bookings') }}
                        </h4>

                        <div
                            class=" d-flex align-items-center d-inline-block w-auto selected-booking {{ request()->status ? '' : 'd-none' }}">
                            <span class="text-capitalize">{{ request()->status }}</span>
                            <span class="ms-1 lh-1 fs-5 fw-normal close " id="cancelButton">&times;</span>
                        </div>
                    </div>
                    <form class="mb-0">
                        <div class="form-group d-flex align-items-center">
                            <select class="form-select select-2" id="booking_status"
                                data-placeholder="Select Booking Status">
                                <option></option>
                                @foreach ($bookingStatus as $status)
                                <option value="{{ $status?->slug }}"
                                    {{ request()->status == $status->slug ? 'selected' : '' }}>
                                    {{ $status?->name }}
                                </option>
                                @endforeach
                            </select>



                        </div>
                    </form>

                </div>
                @endif
                <div class="booking-sec-box ratio_70">
                    <ul class="booking-list">
                        @forelse($bookings as $booking)
                        @php
                        $parent_booking_number = $booking?->parent?->booking_number;
                        $booking_number = $booking?->booking_number;
                        $isMultipleSubBookigs = true;
                        @endphp
                        @if($booking->parent)
                        @if($booking?->parent?->sub_bookings->count() <= 1)
                            @php
                            $parent_booking_number = null;
                            $booking_number= $booking?->booking_number;
                            $isMultipleSubBookigs = false;
                            @endphp
                            @endif
                            @endif
                            <li class="booking-box">
                                <div class="booking-top-box">
                                    <div class="service-image">
                                        <a href="{{ route('frontend.service.details', $booking?->service?->slug) }}">
                                            <img src="{{ $booking?->service?->web_img_thumb_url }}" alt="feature"
                                                class="bg-img">
                                        </a>
                                    </div>
                                    <div class="service-status">
                                        <div class="w-100">
                                            <div class="status">
                                               
                                                <button type="button" class="status-btn" data-bs-toggle="modal"
                                                    data-bs-target="#bookingDetailModal-{{ $booking?->id }}">#{{ $parent_booking_number  ?? $booking_number }}</button>
                                               
                                                
                                                <div class="badge {{ $booking?->booking_status?->slug }}-badge">
                                                    {{ $booking?->booking_status?->name }}
                                                </div>
                                            </div>
                                            <ul class="data">
                                                @if($isMultipleSubBookigs)
                                                <li>
                                                    <div class="label">
                                                        <span> {{ __('frontend::static.bookings.sub_bookings_id') }} </span>

                                                    </div>
                                                    <span
                                                        class="value">{{$booking_number }}</span>
                                                </li>
                                                @endif
                                                <li>
                                                    <div class="label">
                                                        <span> {{ __('frontend::static.bookings.date_time') }} </span>
                                                        @if ($booking?->booking_status?->slug == BookingEnumSlug::PENDING)
                                                        <button type="button" class="date-time-location-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#datetimeModal-{{ $booking?->booking_number }}">
                                                            <i class="iconsax" icon-name="edit-2"></i>
                                                        </button>
                                                        @endif
                                                    </div>
                                                    <span
                                                        class="value">{{ \Carbon\Carbon::parse($booking?->date_time)->format('j F, Y - g:i a') }}</span>
                                                </li>
                                                <li>
                                                    <div class="label">
                                                        <span> {{ __('frontend::static.bookings.location') }} </span>
                                                        @if ($booking?->booking_status?->slug == BookingEnumSlug::PENDING)
                                                        <button type="button" class="date-time-location-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#quicklocationModal-{{ $booking?->booking_number }}">
                                                            <i class="iconsax" icon-name="edit-2"></i>
                                                        </button>
                                                        @endif
                                                    </div>
                                                    <span
                                                        class="value location">{{ $booking?->address?->state?->name }}
                                                        -
                                                        {{ $booking?->address?->country?->name }}</span>
                                                    @if ($booking?->booking_status?->slug == BookingEnumSlug::PENDING)
                                                    <!-- Quick Location modal -->
                                                    <div class="modal fade address-modal"
                                                        id="quicklocationModal-{{ $booking?->booking_number }}"
                                                        tabindex="-1" aria-labelledby="quicklocationModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <form
                                                                    action="{{ route('frontend.booking.update', $booking?->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-header">
                                                                        <h3 class="modal-title"
                                                                            id="quicklocationModalLabel-{{ $booking?->booking_number }}">
                                                                            {{ __('frontend::static.bookings.saved_location') }}
                                                                        </h3>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body pb-0">
                                                                        <div class="service-booking p-0">
                                                                            @includeIf('frontend.booking.select-address')
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-solid">Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </li>
                                                <li>
                                                    <div class="label">
                                                        <span>{{ __('frontend::static.bookings.payment') }}</span>
                                                    </div>
                                                    <span
                                                        class="badge payment-status-{{ $booking?->payment_status }}">{{ $booking?->payment_status }}</span>
                                                </li>
                                                <li>
                                                    <div class="label">
                                                        <span>{{ __('frontend::static.bookings.select_servicemen') }}</span>
                                                    </div>
                                                    <span class="value">{{ $booking?->total_servicemen }}
                                                        {{ __('frontend::static.bookings.servicemen') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="border-solid"></div>
                                    </div>
                                </div>
                                <div class="booking-bottom-box">
                                    <div class="service-title booking-title">
                                        <h4>
                                            <a
                                                href="{{ route('frontend.service.details', $booking?->service?->slug) }}">
                                                {{ $booking?->service?->title }}
                                            </a>
                                        </h4>

                                        <div class="d-flex align-items-center gap-1">
                                            <span>{{ $defaultSymbol }}
                                                {{ Helpers::covertDefaultExchangeRate($booking?->service->service_rate) }}</span>
                                            <small class="text-danger">({{ $booking?->service?->discount }}%
                                                off)</small>
                                        </div>
                                    </div>
                                    <div class="selected-men">
                                        @if (count($booking?->servicemen))
                                        @php
                                        $servicemen = $booking?->servicemen ?? [];
                                        @endphp
                                        @foreach ($servicemen as $serviceman)
                                        <div class="servicemen-list-item">
                                            <div class="list">
                                                <img src="{{ $serviceman?->media?->first()?->original_url }}"
                                                    alt="feature" class="img-45">
                                                <div>
                                                    <p>{{ __('frontend::static.bookings.servicemen') }}</p>
                                                    <ul>
                                                        <li>
                                                            <h5>{{ $serviceman?->name }}</h5>
                                                        </li>
                                                        <li>
                                                            @if ($serviceman?->reviews_count)
                                                            <div class="rate">
                                                                <img src="{{ asset('frontend/images/svg/star.svg') }}"
                                                                    alt="star" class="img-fluid star">
                                                                <small>{{ $serviceman?->ratings_count }}
                                                                    ({{ $serviceman?->reviews_count }})
                                                                </small>
                                                            </div>
                                                            @else
                                                            <div>{{ __('frontend::static.bookings.none') }}
                                                            </div>
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </li>
                            @empty
                            <div class="no-data-found">
                                <img class="img-fluid no-data-img" src="{{ asset('frontend/images/no-data.svg') }}"
                                    alt="">
                                <p>{{ __('frontend::static.bookings.not_found') }}</p>
                            </div>
                            @endforelse
                    </ul>
                </div>
                <div class="col-12">
                    @if ($bookings->lastPage() > 1)
                    <div class="pagination-main">
                        <ul class="pagination mt-0">
                            {!! $bookings->links() !!}
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Service List Section End -->


@if ($bookings ?? count([]))
@foreach ($bookings as $booking)


@php
$parent_booking_number = $booking?->parent?->booking_number;

$booking_number = $booking?->booking_number;
$isMultipleSubBookigs = true;
@endphp
@if($booking->parent)
@if($booking?->parent?->sub_bookings->count() <= 1)
    @php
    $booking_number=$booking->parent->booking_number;
    $isMultipleSubBookigs = false;
    @endphp
    @endif
@endif

    <!-- Booking Details Modal -->
    <div class="modal fade accepted-modal" id="bookingDetailModal-{{ $booking?->id }}" tabindex="-1"
        aria-labelledby="acceptedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="bookingDetailModalLabel-{{ $booking?->id }}">
                        {{ $booking?->booking_status?->name }} {{ __('frontend::static.bookings.bookings') }}
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body custom-scroll booking-sec">
                    <div class="card">
                        <div class="row g-3">
                            <div class="col-sm-5 col-12 ratio_70">
                                <div class="overflow-hidden b-r-5">

                                    <a class="card-img position-relative">
                                        <img src="{{ $booking?->service?->web_img_thumb_url }}"
                                            alt="{{ $booking?->service?->title }}" class="bg-img br-8">
                                        @php
                                        $category =
                                        $booking?->service?->categories()?->first()->toArray() ?? [];
                                        @endphp
                                    </a>
                                    @if (count($category))
                                    <div class="badge primary-badge">
                                        {{ $category['title'] }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-7 col-12">
                                <div class="status">
                                    <h5>{{ $booking?->service?->title }}</h5>

                                    @if($booking_number)
                                    <span class="status-btn">#{{$parent_booking_number ?? $booking_number }}</span>
                                    
                                    @endif

                                </div>
                                <div class="view-status">
                                    <div class="rate">
                                        @if ($booking?->service?->rating_count)
                                        <img src="{{ asset('frontend/images/svg/star.svg') }}" alt="star"
                                            class="img-fluid star">
                                        <small>{{ $booking?->service?->rating_count }}</small> <span>
                                            ({{ $booking?->service?->reviews_count }})
                                        </span>
                                        @endif
                                    </div>
                                    <button type="button" class="badge primary-light-badge"
                                        data-bs-toggle="modal"
                                        data-bs-target="#bookingStatusModal-{{ $booking?->booking_number }}">
                                        {{ __('frontend::static.bookings.view_status') }}
                                        <i class="iconsax" icon-name="arrow-right"></i>
                                    </button>
                                </div>
                                <div class="border-dashed mb-2"></div>
                                <ul class="data">
                                    @if($isMultipleSubBookigs)
                                    <li>
                                        <div class="label">
                                            <span>{{ __('frontend::static.bookings.sub_booking-id') }}</span>
                                        </div>
                                        <span class="value">{{  $booking_number  }}</span>
                                    </li>
                                    @endif
                                    <li>
                                        <div class="label">
                                            <span>{{ __('frontend::static.bookings.date_time') }}</span>
                                        </div>
                                        <span
                                            class="value">{{ \Carbon\Carbon::parse($booking?->service?->date_time)->format('j F, Y - g:i a') }}</span>
                                    </li>
                                    <li>
                                        <div class="label">
                                            <span>{{ __('frontend::static.bookings.location') }}</span>
                                        </div>
                                        <span class="value location">{{ $booking?->address?->state?->name }} -
                                            {{ $booking?->address?->country?->name }}</span>
                                    </li>
                                    <li>
                                        <div class="label">
                                            <span>{{ __('frontend::static.bookings.payment') }}</span>
                                        </div>
                                        <span
                                            class="badge payment-status-{{ $booking?->payment_status }}">{{ $booking?->payment_status }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <p class="m-0">
                                    {{ $booking?->service?->description }}
                                </p>
                            </div>
                            @if ($booking?->booking_status?->slug == BookingEnumSlug::PENDING)
                            <div class="col-12">
                                <div class="status-note">
                                    <span>{{ __('frontend::static.bookings.status') }}:
                                    </span>{{ __('frontend::static.bookings.provider_approved') }}
                                </div>
                            </div>
                            @endif
                            <div class="col-12">
                                <div>
                                    <div class="table-responsive custom-scroll">
                                        <table class="table booking-table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">{{ __('frontend::static.bookings.name') }}
                                                    </th>
                                                    <th scope="col">{{ __('frontend::static.bookings.rate') }}
                                                    </th>
                                                    <th scope="col">
                                                        {{ __('frontend::static.bookings.experience') }}
                                                    </th>
                                                    <th scope="col">
                                                        {{ __('frontend::static.bookings.contact') }}
                                                    </th>
                                                    <th scope="col">
                                                        {{ __('frontend::static.bookings.action') }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    @if ($booking?->provider)
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <img src="{{ $booking->provider?->media?->first()?->original_url ?? asset('frontend/images/avatar/8.png') }}"
                                                                alt="feature" class="img-45">
                                                            <div>
                                                                <p class="m-0">
                                                                    {{ $booking?->provider?->name }}
                                                                </p>
                                                                <span>Provider</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($booking?->provider?->rating_count)
                                                        <div class="rate m-0">
                                                            <img src="{{ asset('frontend/images/svg/star.svg') }}"
                                                                alt="star" class="img-fluid star">
                                                            <span>{{ $booking?->provider?->rating_count }}
                                                                ({{ $booking?->provider?->reviews_count }})</span>
                                                        </div>
                                                        @else
                                                        <span>{{ __('frontend::static.bookings.none') }}</span>
                                                        @endif
                                                    </td>

                                                    <td>{{ $booking?->provider?->experience_duration }}
                                                        {{ $booking?->provider?->experience_interval }}
                                                    </td>
                                                    <td>-</td>
                                                    <td>
                                                        <button type="button" class="profile-view"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#providerDetailModal-{{ $booking?->booking_number }}">
                                                            <i class="iconsax" icon-name="eye"></i>
                                                        </button>
                                                    </td>
                                                    @endif
                                                </tr>
                                                @if (count($booking?->servicemen))
                                                @php
                                                $servicemen = $booking?->servicemen;
                                                @endphp
                                                @foreach ($servicemen as $serviceman)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <img src="{{ $serviceman?->media?->first()?->original_url }}"
                                                                alt="{{ $serviceman?->name }}"
                                                                class="img-45">
                                                            <div>
                                                                <p class="m-0">
                                                                    {{ $serviceman?->name }}
                                                                </p>
                                                                <span>Provider</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($booking?->provider?->rating_count)
                                                        <div class="rate m-0">
                                                            <img src="{{ asset('frontend/images/svg/star.svg') }}"
                                                                alt="star" class="img-fluid star">
                                                            <span>{{ $serviceman?->rating_count }}
                                                                ({{ $serviceman?->reviews_count }})
                                                            </span>
                                                        </div>
                                                        @else
                                                        <span>{{ __('frontend::static.bookings.none') }}</span>
                                                        @endif
                                                    </td>

                                                    <td>{{ $serviceman?->experience_duration }}
                                                        {{ $serviceman?->experience_interval }}
                                                    </td>
                                                    <td>
                                                        <a href="https://web.whatsapp.com/"
                                                            target="_blank" class="chat">
                                                            <i class="iconsax" icon-name="messages-2"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="profile-view"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#servicemanDetailModal-{{ $booking?->booking_number }}">
                                                            <i class="iconsax" icon-name="eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="bill-summary">
                                    <label>
                                        {{ __('frontend::static.bookings.bill_summary') }}
                                    </label>
                                    <ul class="charge">

                                        <li>
                                            <p>{{ $booking?->total_servicemen }}
                                                {{ __('frontend::static.bookings.servicemen_charges') }}
                                                ({{ $defaultSymbol }}{{ Helpers::covertDefaultExchangeRate($booking?->per_serviceman_charge) }}
                                                {{ __('frontend::static.bookings.per_serviceman') }})
                                            </p>
                                            <span>{{ $defaultSymbol }}{{ Helpers::covertDefaultExchangeRate($booking?->total_extra_servicemen_charge) }}</span>
                                        </li>
                                        <li>
                                            <p>{{ __('frontend::static.bookings.tax') }}</p>
                                            <span>{{ $defaultSymbol }}{{ Helpers::covertDefaultExchangeRate($booking?->tax) }}</span>
                                        </li>
                                        <li>
                                            <p>{{ __('frontend::static.bookings.platform_fees') }}</p>
                                            <span>{{ $defaultSymbol }}{{ Helpers::covertDefaultExchangeRate($booking?->platform_fees) }}</span>
                                        </li>
                                        <li>
                                            @php
                                            $extraChargeAmount = Helpers::getTotalExtraCharges(
                                            $booking?->id,
                                            );
                                            @endphp
                                            <p>{{ __('frontend::static.bookings.extra_charges') }}</p>
                                            <span>{{ $defaultSymbol }}{{ Helpers::covertDefaultExchangeRate($extraChargeAmount) }}</span>
                                        </li>
                                    </ul>
                                    <ul class="total">
                                        <li>
                                            <p>{{ __('frontend::static.bookings.total_amount') }}</p>
                                            <span>{{ $defaultSymbol }}{{ Helpers::covertDefaultExchangeRate($booking?->total + $extraChargeAmount) }}</span>
                                        </li>
                                    </ul>
                                    <div class="circle"></div>
                                </div>
                            </div>

                            @if (count($booking?->extra_charges ?? []))
                            @php
                            $extraCharges = $booking?->extra_charges ?? [];
                            @endphp
                            @foreach ($extraCharges as $extraCharge)
                            <div class="col-12">
                                <div class="extra-service">
                                    <label>
                                        {{ __('frontend::static.bookings.extra_service_details') }}
                                    </label>
                                    <div class="total-amount">
                                        <div>
                                            <h4>{{ $extraCharge?->title }}</h4>
                                            <p>{{ __('frontend::static.bookings.no_of_service_done') }}:
                                                <span>{{ $extraCharge?->no_service_done }}
                                                    ({{ $defaultSymbol }}
                                                    {{ $extraCharge?->per_service_amount }}
                                                    {{ __('frontend::static.bookings.per_service') }})
                                                </span>
                                            </p>
                                        </div>
                                        <div class="receipt">
                                            <img src="{{ asset('frontend/images/svg/receipt-add.svg') }}"
                                                alt="receipt" class="receipt-img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif

                            <div class="col-12">
                                <div class="payment-summary">
                                    <label>
                                        {{ __('frontend::static.bookings.payment_summary') }}
                                    </label>
                                    <ul class="charge">
                                        <li>
                                            <p>{{ __('frontend::static.bookings.method_type') }}</p>
                                            <span>{{ ucfirst($booking?->payment_method) }}</span>
                                        </li>
                                        <li>
                                            <p>{{ __('frontend::static.bookings.status') }}</p>
                                            <span
                                                class="badge payment-status-{{ $booking?->payment_status }}">{{ $booking?->payment_status }}</span>
                                        </li>
                                    </ul>
                                    <div class="circle"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if ($booking?->booking_status?->slug == BookingEnumSlug::PENDING)
                    <button type="button" class="btn btn-outline" data-bs-toggle="modal"
                        data-bs-target="#cancelReasonModal-{{ $booking?->id }}">
                        {{ __('frontend::static.bookings.cancel') }}
                    </button>
                    @elseif($booking?->booking_status?->slug == BookingEnumSlug::ON_THE_WAY)
                    <button type="button" class="btn btn-solid" data-bs-toggle="modal"
                        data-bs-target="#startServiceModal-{{ $booking?->id }}">
                        {{ __('frontend::static.bookings.start_service') }}
                    </button>
                    @elseif(in_array($booking?->booking_status?->slug, [BookingEnumSlug::ON_GOING, BookingEnumSlug::ON_HOLD]))
                    @if ($booking?->booking_status?->slug == BookingEnumSlug::ON_GOING)
                    <button type="button" class="btn btn-solid-danger" data-bs-toggle="modal"
                        data-bs-target="#pauseServiceModal-{{ $booking?->id }}">
                        {{ __('frontend::static.bookings.pause') }}
                    </button>
                    @elseif($booking?->booking_status?->slug == BookingEnumSlug::ON_HOLD)
                    <button type="button" class="btn btn-solid-success" data-bs-toggle="modal"
                        data-bs-target="#restartServiceModal-{{ $booking?->id }}">
                        {{ __('frontend::static.bookings.restart') }}
                    </button>
                    @endif
                    @if (Helpers::isExtraChargePaymentPending($booking?->id))
                    <form action="{{ route('frontend.payment.now') }}" method="post">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="booking_id" value="{{ $booking?->id }}" />
                        <button type="submit" class="btn btn-solid-success">
                            {{ __('frontend::static.bookings.pay') }}
                            {{ $defaultSymbol }}{{ Helpers::covertDefaultExchangeRate($extraChargeAmount) }}
                            {{ __('frontend::static.bookings.to_complete') }}
                        </button>
                    </form>
                    @else
                    <button type="button" class="btn btn-solid" data-bs-toggle="modal"
                        data-bs-target="#completedServiceModal-{{ $booking?->id }}">
                        {{ __('frontend::static.bookings.complete') }}
                    </button>
                    @endif
                    @elseif($booking?->booking_status?->slug == BookingEnumSlug::COMPLETED)
                    <a class="btn btn-outline" href="{{ $booking?->invoice_url }}">
                        {{ __('frontend::static.bookings.download_bill') }}
                    </a>
                    @if ($booking?->service?->reviews?->isEmpty())
                    <button type="button" class="btn btn-solid" data-bs-toggle="modal"
                        data-bs-target="#addReviewModal-{{ $booking?->id }}">
                        {{ __('frontend::static.bookings.rate_us') }}
                    </button>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Status modal -->
    <div class="modal fade status-modal" id="bookingStatusModal-{{ $booking?->booking_number }}" tabindex="-1"
        aria-labelledby="bookingStatusModalLabel-{{ $booking?->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header ps-4">
                    <h3 class="modal-title" id="bookingStatusModalLabel-{{ $booking?->id }}">
                        {{ __('frontend::static.bookings.booking_status') }}
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    <button type="button" class="modal-back" data-bs-toggle="modal"
                        data-bs-target="#bookingDetailModal-{{ $booking?->id }}">
                        <i class="iconsax" icon-name="chevron-left"></i>
                    </button>
                </div>
                <div class="modal-body custom-scroll">
                    <div class="input-group">
                        <div class="pattern-input form-control">
                            {{ __('frontend::static.bookings.to_complete') }}
                        </div>
                        <a href="javascript:void(0)" class="pattern-btn-1">#{{ $booking?->booking_number }}</a>
                    </div>

                    <div class="status-history">
                        <ul>
                            @if (count($booking?->booking_status_logs ?? []))
                            @php
                            $statusLogs = $booking?->booking_status_logs;
                            @endphp
                            @foreach ($statusLogs as $status)
                            <li class="{{ $loop->first ? 'recent' : '' }}">
                                <div class="d-flex align-items-center">
                                    <div class="activity-dot"></div>
                                    <i class="iconsax" icon-name="arrow-right"></i>
                                </div>
                                <h5 class="status-time">
                                    {{ \Carbon\Carbon::parse($status?->created_at)->diffForHumans() }}
                                </h5>
                                <div class="status-main">
                                    <p class="status-title">{{ $status?->title }}</p>
                                    <p class="status-des">{{ $status?->description }}</p>
                                </div>
                                <div class="dashed-border"></div>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($booking?->provider)
    @php
    $provider = $booking?->provider;
    @endphp
    <!-- Provider detail modal-->
    <div class="modal fade servicemen-detail-modal" id="providerDetailModal-{{ $booking?->booking_number }}"
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel-{{ $provider?->id }}"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <i class="iconsax arrow" icon-name="chevron-left"></i>
                    <h3 class="modal-title" id="providerDetailModalLabel-{{ $booking?->booking_number }}">
                        {{ __('frontend::static.bookings.provider_detail') }}
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="provider-card">
                        <div class="provider-detail">
                            <div class="provider-content">
                                <div class="profile-bg"></div>
                                <div class="profile">
                                    <img src="{{ $provider?->media->first()->getUrl() }}" alt="girl"
                                        class="img">
                                    <div class="d-flex align-content-center gap-2 mt-2">
                                        <h3>{{ $provider?->name }}</h3>
                                        <div class="rate">
                                            <img src="{{ asset('frontend/images/svg/star.svg') }}"
                                                alt="star" class="img-fluid star">
                                            <small>{{ $provider?->review_ratings ?? 'Unrated' }}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <p class="text-light">
                                            @if ($provider?->experience_duration)
                                            {{ $provider?->experience_duration }}
                                            {{ $provider?->experience_interval }}
                                            {{ __('frontend::static.bookings.of_experience') }}
                                            @else
                                            {{ __('frontend::static.bookings.fresher') }}
                                            @endif
                                        </p>
                                        <div class="location">
                                            <i class="iconsax" icon-name="location"></i>
                                            <h5>{{ $provider?->primary_address?->state?->name }} -
                                                {{ $provider?->primary_address?->country?->name }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="view br-6 mt-3">
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <span
                                            class="text-title">{{ __('frontend::static.bookings.services_delivered') }}</span>
                                        <small class="value"> {{ $provider?->served }}
                                            {{ __('frontend::static.bookings.served') }}</small>
                                    </div>
                                </div>
                                <div class="information">
                                    <div>
                                        <p class="mt-3 mb-2">
                                            {{ __('frontend::static.bookings.personal_info') }}
                                        </p>
                                        <div class="profile-info">
                                            <div>
                                                <label>
                                                    <i class="iconsax" icon-name="mail"></i>
                                                    {{ __('frontend::static.bookings.mail') }}
                                                </label>
                                                <p>{{ $provider?->email }}</p>
                                            </div>
                                            <div>
                                                <label>
                                                    <i class="iconsax" icon-name="phone"></i>
                                                    {{ __('frontend::static.bookings.call') }}
                                                </label>
                                                <p>+{{ $provider?->code }} {{ $provider?->phone }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($provider->knownLanguages?->toArray())
                                    <div>
                                        <p class="mt-3 mb-2">
                                            {{ __('frontend::static.bookings.known_languages') }}
                                        </p>
                                        @php
                                        $knownLanguages = $provider->knownLanguages;
                                        @endphp
                                        <div class="d-flex align-content-center gap-3 mt-2">
                                            @foreach ($knownLanguages as $language)
                                            <button
                                                class="btn btn-solid-gray">{{ $language?->key }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    @if (count($provider->expertise))
                                    <div>
                                        <p class="mt-3 mb-2">
                                            {{ __('frontend::static.bookings.experties_in') }}
                                        </p>
                                        <ul class="expert">
                                            @foreach ($provider?->expertise as $expertise)
                                            <li>{{ $expertise?->title }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($booking?->servicemen)
    @php
    $servicemen = $booking?->servicemen;
    @endphp
    @foreach ($servicemen as $serviceman)
    <!-- Servicemen detail modal-->
    <div class="modal fade servicemen-detail-modal"
        id="servicemanDetailModal-{{ $booking?->booking_number }}" tabindex="-1"
        aria-labelledby="staticBackdropLabel-{{ $serviceman?->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"
                        id="servicemanDetailModalLabel-{{ $booking?->booking_number }}">
                        {{ __('frontend::static.bookings.servicema_details') }}
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="provider-card">
                        <div class="provider-detail">
                            <div class="provider-content">
                                <div class="profile-bg"></div>
                                <div class="profile">
                                    <img src="{{ $serviceman?->media->first()->getUrl() }}"
                                        alt="girl" class="img">
                                    <div class="d-flex align-content-center gap-2 mt-2">
                                        <h3>{{ $serviceman?->name }}</h3>
                                        <div class="rate">
                                            <img src="{{ asset('frontend/images/svg/star.svg') }}"
                                                alt="star" class="img-fluid star">
                                            <small>{{ $serviceman?->review_ratings ?? 'Unrated' }}</small>
                                        </div>
                                    </div>
                                    aria-labelledby="staticBackdropLabel-{{ $serviceman?->id }}"
                                    aria-hidde
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($provider?->experience_duration)
                                        {{ $provider?->experience_duration }}
                                        {{ $provider?->experience_interval }}
                                        {{ __('frontend::static.bookings.of_experience') }}
                                        @else
                                        {{ __('frontend::static.bookings.fresher') }}
                                        @endif
                                        <div class="location">
                                            <i class="iconsax" icon-name="location"></i>
                                            <h5>{{ $serviceman?->primary_address?->state?->name }} -
                                                {{ $serviceman?->primary_address?->country?->name }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="view br-6 mt-3">
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <span
                                            class="text-title">{{ __('frontend::static.bookings.services_delivered') }}</span>
                                        <small class="value"> {{ $serviceman?->served }} served</small>
                                    </div>
                                </div>
                                <div class="information">
                                    <div>
                                        <p class="mt-3 mb-2">
                                            {{ __('frontend::static.bookings.personal_info') }}
                                        </p>
                                        <div class="profile-info">
                                            <div>
                                                <label>
                                                    <i class="iconsax" icon-name="mail"></i>
                                                    {{ __('frontend::static.bookings.mail') }}
                                                </label>
                                                <p>{{ $serviceman?->email }}</p>
                                            </div>
                                            <div>
                                                <label>
                                                    <i class="iconsax" icon-name="phone"></i>
                                                    {{ __('frontend::static.bookings.call') }}s
                                                </label>
                                                <p>+{{ $serviceman?->code }} {{ $serviceman?->phone }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($serviceman->knownLanguages?->toArray())
                                    <div>
                                        <p class="mt-3 mb-2">
                                            {{ __('frontend::static.bookings.known_languges') }}
                                        </p>
                                        @php
                                        $knownLanguages = $serviceman->knownLanguages;
                                        @endphp
                                        <div class="d-flex align-content-center gap-3 mt-2">
                                            @foreach ($knownLanguages as $language)
                                            <button
                                                class="btn btn-solid-gray">{{ $language?->key }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                    @if (count($serviceman->expertise))
                                    <div>
                                        <p class="mt-3 mb-2">Expertise in</p>
                                        <ul class="expert">
                                            @foreach ($serviceman?->expertise as $expertise)
                                            <li>{{ $expertise?->title }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    @if ($booking?->booking_status?->slug == BookingEnumSlug::PENDING)
    @includeIf('frontend.booking.date-time', ['booking' => $booking])
    @elseif($booking?->booking_status?->slug == BookingEnumSlug::ON_THE_WAY)
    <!-- Start Service modal -->
    <div class="modal fade start-service-modal" id="startServiceModal-{{ $booking?->id }}" tabindex="-1"
        aria-labelledby="startServiceModalLabel-{{ $booking?->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="cancelReasonForm" action="{{ route('frontend.booking.update', $booking?->id) }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="startServiceModalLabel">
                            {{ __('frontend::static.bookings.start_service') }}
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body-content">
                            <img src="{{ asset('frontend/images/gif/rocket.gif') }}" alt="rocket">
                            <img src="{{ asset('frontend/images/svg/Ellipse.svg') }}" alt="ellipse"
                                class="ellipse">
                        </div>
                        <p class="my-3">{{ __('frontend::static.bookings.start_service_confirm') }}</p>

                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="booking_status" value="on-going" />
                        <button type="submit" class="btn btn-solid">
                            {{ __('frontend::static.bookings.yes_start') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @elseif(in_array($booking?->booking_status?->slug, [BookingEnumSlug::ON_GOING, BookingEnumSlug::ON_HOLD]))
    @if ($booking?->booking_status?->slug == BookingEnumSlug::ON_GOING)
    <!-- Pause Service modal -->
    <div class="modal fade pause-service-modal" id="pauseServiceModal-{{ $booking?->id }}"
        tabindex="-1" aria-labelledby="pauseServiceModalLabel-{{ $booking?->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="pauseServiceModalLabel">
                        {{ __('frontend::static.bookings.hold_service') }}
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body-content">
                        <img src="{{ asset('frontend/images/svg/Ellipse.svg') }}" alt="ellipse"
                            class="ellipse">
                        <img src="{{ asset('frontend/images/gif/pause.gif') }}" alt="pause">
                        <img src="{{ asset('frontend/images/svg/hold.svg') }}" alt="hold"
                            class="hold">
                    </div>
                    <p class="my-3">{{ __('frontend::static.bookings.hold_confirm') }}</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-solid" data-bs-toggle="modal"
                        data-bs-target="#pauseServiceReasonModal-{{ $booking?->id }}">
                        {{ __('frontend::static.bookings.yes_pause_service') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Pause reason modal -->
    <div class="modal fade pause-reason-service-modal" id="pauseServiceReasonModal-{{ $booking?->id }}"
        tabindex="-1" aria-labelledby="pauseServiceReasonModalLabel-{{ $booking?->id }}"
        aria-hidden="true">
        <form action="{{ route('frontend.booking.update', $booking?->id) }}" method="POST">
            <div class="modal-dialog modal-dialog-centered">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="pauseServiceReasonModalLabel">
                            {{ __('frontend::static.bookings.reason_hold_service') }}
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="cancel-content">
                            <label>
                                {{ __('frontend::static.bookings.reason') }}
                            </label>
                            <textarea class="form-control form-control-white" id="reason" name="reason" rows="5"
                                placeholder="Write reason here.."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="booking_status" value="on-hold" />
                        <button type="submit" class="btn btn-solid">
                            {{ __('frontend::static.bookings.submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @elseif($booking?->booking_status?->slug == BookingEnumSlug::ON_HOLD)
    <!-- Restart Service modal -->
    <div class="modal fade restart-service-modal" id="restartServiceModal-{{ $booking?->id }}"
        tabindex="-1" aria-labelledby="restartServiceModalLabel-{{ $booking?->id }}"
        aria-hidden="true">
        <form action="{{ route('frontend.booking.update', $booking?->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="restartServiceModalLabel">
                            {{ __('frontend::static.bookings.restart_service') }}
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body-content">
                            <img src="{{ asset('frontend/images/gif/rocket.gif') }}" alt="rocket">
                            <img src="{{ asset('frontend/images/svg/Ellipse.svg') }}" alt="ellipse"
                                class="ellipse">
                        </div>
                        <p class="my-3"> {{ __('frontend::static.bookings.restart_confirm') }}</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="booking_status" value="on-going" />
                        <button type="submit" class="btn btn-solid">
                            {{ __('frontend::static.bookings.restart_service') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endif

    @if (!Helpers::isExtraChargePaymentPending($booking?->id))
    <!-- Complete Service modal -->
    <div class="modal fade completed-service-modal" id="completedServiceModal-{{ $booking?->id }}"
        tabindex="-1" aria-labelledby="completedServiceModalLabel-{{ $booking?->id }}"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('frontend.booking.update', $booking?->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="completedServiceModalLabel">
                            {{ __('frontend::static.bookings.complete_booking') }}
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body-content">
                            <img src="{{ asset('frontend/images/svg/succcess-tick.svg') }}"
                                alt="tick" class="success-tick">
                            <img src="{{ asset('frontend/images/girl-on-chair.png') }}" alt="ellipse"
                                class="girl-on-chair">
                        </div>
                        <p class="my-3"> {{ __('frontend::static.bookings.complete_confirm') }}</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="booking_status" value="completed" />
                        <button type="button" class="btn btn-outline">
                            {{ __('frontend::static.bookings.no') }}
                        </button>
                        <button type="submit" class="btn btn-solid">
                            {{ __('frontend::static.bookings.yes') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
    @elseif($booking?->booking_status?->slug == BookingEnumSlug::COMPLETED)
    <!-- Add review modal -->
    <div class="modal fade review-modal" id="addReviewModal-{{ $booking?->id }}" tabindex="-1"
        aria-labelledby="addReviewModalLabel-{{ $booking?->id }}" aria-hidden="true">
        <form action="{{ route('frontend.account.review.store') }}" method="post">
            @csrf
            @method('POST')
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addReviewModalLabel-{{ $booking?->id }}">
                            {{ __('frontend::static.bookings.add_review') }}
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="rate-content">
                            <p>{{ __('frontend::static.bookings.review_description') }}</p>
                            <div class="border-dashed my-3"></div>
                            <input type="hidden" name="rating" id="emoji-rating-{{ $booking->id }}"
                                class="emoji-rating" value="">
                            <div class="form-group">
                                <label>{{ __('frontend::static.bookings.explain_in_emoji') }}</label>
                                <ul class="emoji-tab">
                                    <li class="emoji-icon" data-rating="1">
                                        <div class="emojis">
                                            <img src="{{ asset('frontend/images/svg/Bad-1.svg') }}"
                                                alt="" class="emoji deactive">
                                            <img src="{{ asset('frontend/images/svg/Bad.svg') }}"
                                                alt="" class="emoji active">
                                        </div>
                                        <h4>{{ __('frontend::static.bookings.bad') }}</h4>
                                    </li>
                                    <li class="emoji-icon" data-rating="2">
                                        <div class="emojis">
                                            <img src="{{ asset('frontend/images/svg/Okay-1.svg') }}"
                                                alt="" class="emoji deactive">
                                            <img src="{{ asset('frontend/images/svg/Okay.svg') }}"
                                                alt="" class="emoji active">
                                        </div>
                                        <h4>{{ __('frontend::static.bookings.okay') }}</h4>
                                    </li>
                                    <li class="emoji-icon" data-rating="3">
                                        <div class="emojis">
                                            <img src="{{ asset('frontend/images/svg/Good-1.svg') }}"
                                                alt="" class="emoji deactive">
                                            <img src="{{ asset('frontend/images/svg/Good.svg') }}"
                                                alt="" class="emoji active">
                                        </div>
                                        <h4>{{ __('frontend::static.bookings.good') }}</h4>
                                    </li>
                                    <li class="emoji-icon" data-rating="4">
                                        <div class="emojis">
                                            <img src="{{ asset('frontend/images/svg/Amazing-1.svg') }}"
                                                alt="" class="emoji deactive">
                                            <img src="{{ asset('frontend/images/svg/Amazing.svg') }}"
                                                alt="" class="emoji active">
                                        </div>
                                        <h4>{{ __('frontend::static.bookings.amazing') }}</h4>
                                    </li>
                                    <li class="emoji-icon" data-rating="5">
                                        <div class="emojis">
                                            <img src="{{ asset('frontend/images/svg/Excellent-1.svg') }}"
                                                alt="" class="emoji deactive">
                                            <img src="{{ asset('frontend/images/svg/Excellent.svg') }}"
                                                alt="" class="emoji active">
                                        </div>
                                        <h4>{{ __('frontend::static.bookings.excellent') }}</h4>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group">
                                <label
                                    for="rating">{{ __('frontend::static.bookings.say_something_more') }}</label>
                                <textarea name="rate" id="rating" rows="5" placeholder="Write reason here.."
                                    class="form-control form-control-white"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="service_id" value="{{ $booking?->service_id }}" />
                        <button type="submit"
                            class="btn btn-solid">{{ __('frontend::static.bookings.submit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>cancelButton
    @endif
    @endforeach
    @endif

    @includeIf('frontend.address.add')










    <!-- Add Content Section -->
    @endsection

    @push('js')
    <!-- Flat-picker js -->
    <script src="{{ asset('frontend/js/flat-pickr/flatpickr.js') }}"></script>
    <script src="{{ asset('frontend/js/flat-pickr/custom-flatpickr.js') }}"></script>

    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $(".emoji-tab .emoji-icon").on("click", function() {
                    var selectedRating = $(this).data("rating");
                    var reviewId = $(this).closest('.modal').find('input[name="rating"]').attr('id')
                        .split(
                            '-')[2];
                    $("#emoji-rating-" + reviewId).val(selectedRating);
                    $(".emoji-tab .emoji-icon").removeClass('active');
                    $(this).addClass('active');
                });

                $('.review-modal').on('show.bs.modal', function() {
                    var reviewId = $(this).find('input[name="rating"]').attr('id').split('-')[2];
                    var currentRating = $("#emoji-rating-" + reviewId).val();
                    $(".emoji-tab .emoji-icon").removeClass('active');
                    $(".emoji-tab .emoji-icon[data-rating='" + currentRating + "']").addClass('active');
                });
            });

            $('#clear-all').click(function(e) {
                e.preventDefault();
                window.history.replaceState(null, null, location.pathname);
                location.reload();
            });

            $('#accordion_search_bar').on('keyup', function() {
                let searchTerm = $(this).val().toLowerCase(),
                    hasResults = false;
                $('.category-item').each(function() {
                    let showItem = $(this).find('.name').text().toLowerCase().includes(searchTerm);
                    $(this).toggle(showItem);
                    hasResults = hasResults || showItem;
                });

                $('#no-results-message').toggle(!hasResults);
            });

            var urlParams = new URLSearchParams(window.location.search);
            var providerValues = urlParams.get("categories");
            providerValues?.split(",").forEach(val =>
                $(".form-check-input[value='" + val + "']").prop("checked", true)
            );
            $('.form-check-input').change(function() {
                var selectedIds = $('.form-check-input:checked').map(function() {
                    return this.value;
                }).get().join(',');
                if (selectedIds) {
                    $('#select-category').val(selectedIds);
                }
            });

            $('#booking_status').change(function() {
                const status = $(this).val();
                const url = new URL(window.location.href);
                if (status) {
                    url.searchParams.set('status', status); // Add/replace 'status' parameter
                    window.location.search = url.searchParams.toString(); // Update the URL
                }
            });



            $('.category-item').on('click', function(e) {
                const checkbox = $(this).find('.form-check-input');
                if (e.target !== checkbox[0]) {
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }

                var selectedIds = $('.form-check-input:checked').map(function() {
                    return this.value;
                }).get().join(',');
                if (selectedIds) {
                    $('#select-category').val(selectedIds);
                }
            });

            $('.form-check-input').on('click', function(e) {
                e.stopPropagation();
            });

            $('#booking_status').on('change', function() {
                const cancelButton = $('#cancelButton');
                if (this.value) {
                    cancelButton.removeClass('d-none');
                } else {
                    cancelButton.addClass('d-none');
                }
            });

            $('#cancelButton').on('click', function() {
                const categories = encodeURIComponent(`{{ request('categories') ?? '' }}`);
                const startDate = encodeURIComponent(`{{ request('start_date') ?? '' }}`);
                const endDate = encodeURIComponent(`{{ request('end_date') ?? '' }}`);
                let url = `{{ route('frontend.booking.index') }}?`;
                if (categories) url += `categories=${categories}&`;
                if (startDate) url += `start_date=${startDate}&`;
                if (endDate) url += `end_date=${endDate}&`;

                window.location.href = url.slice(0, -1);

                $(this).addClass('d-none');
            });

        })(jQuery);
    </script>
    @endpush