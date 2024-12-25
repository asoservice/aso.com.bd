@use('app\Helpers\Helpers')
@use('App\Enums\FavouriteListEnum')
@extends('frontend.layout.master')

@section('title', $service->title)
@section('meta_description', $service->meta_description ?? $service?->description)
@section('og_title', $service->meta_title ?? $service?->title)
@section('og_description', $service->meta_description ?? $service?->description)
@section('og_image', $service?->media?->first()?->getUrl())
@section('twitter_title', $service->meta_title ?? $service?->title)
@section('twitter_description', $service->meta_description ?? $service?->description)
@section('twitter_image', $service?->media?->first()?->getUrl())

@section('breadcrumb')
<nav class="breadcrumb breadcrumb-icon">
    <a class="breadcrumb-item" href="{{url('/')}}">{{ __('frontend::static.services.home')}}</a>
    <a class="breadcrumb-item" href="{{ route('frontend.service.index') }}">{{ __('frontend::static.services.services')}}</a>
    <span class="breadcrumb-item active">{{ $service->title }}</span>
</nav>
@endsection


@section('content')
<!-- Service List Section Start -->
<section class="service-list-section">
    <div class="container-fluid-lg">
        <div class="row service-list-content g-sm-4 g-3">
            <div class="col-xxl-8 col-lg-7 col-12">
                <div class="swiper service-detail-slider">
                    <div class="swiper-wrapper">
                        @foreach ($service->web_img_galleries_url as $imageUrl)
                        <div class="swiper-slide ratio_45">
                            <div class="position-relative">
                                <div class="service-img">
                                    @php
                                    $consumerId = auth()->id();
                                    $favouriteServiceId = \App\Models\FavouriteList::where('consumer_id', $consumerId)
                                    ->pluck('service_id')
                                    ->toArray();
                                    @endphp
                                    <img src="{{ $imageUrl}}" alt="offer" class="bg-img">
                                    @auth
                                    <div class="like-icon b-top" id="favouriteDiv" data-service-id="{{ $service?->id }}">
                                        <img class="img-fluid icon outline-icon " src="{{ asset('frontend/images/svg/heart-outline.svg')}}"
                                            alt="whishlist">
                                        <img class="img-fluid icon fill-icon" src="{{ asset('frontend/images/svg/heart-fill.svg')}}" alt="wishlisted">
                                    </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next5"></div>
                    <div class="swiper-button-prev5"></div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="detail-content service-details-content">
                    <div class="title">
                        <h3>{{ $service->title }}</h3>
                        @if ($service->discount)
                        <span class="badge danger-light-badge">
                            {{ $service->discount }}% {{ __('frontend::static.services.discount')}}
                        </span>
                        @endif
                    </div>
                    <p>
                        {{ $service->description }}
                    </p>
                    <div>
                        {!! $service->content !!}
                    </div>
                </div>
            </div>

            @auth
            @includeIf('frontend.inc.modal',['service' => $service])
            @endauth

            <div class="col-xxl-4 col-lg-5 col-12">
                <div class="amount">
                    <div class="amount-header">
                        <span>{{ __('frontend::static.services.amount')}} :</span>
                        <small class="value">{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($service->price) }}</small>
                    </div>
                    <div class="amount-detail">
                        <ul>
                            @if($service?->duration)
                            <li>
                                <i class="iconsax" icon-name="clock"></i>
                                {{ __('frontend::static.services.around')}} {{ $service?->duration }} {{ $service?->duration_unit }}
                            </li>
                            @endif
                            <li>
                                <i class="iconsax" icon-name="user-1-tag"></i>
                                {{ __('frontend::static.services.min')}} {{ $service?->required_servicemen }} {{ __('frontend::static.services.servicemen_required_for')}}
                            </li>
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn book-now-btn btn-solid mt-4" data-bs-toggle="modal" data-bs-toggle="modal" 
        data-bs-target="#bookServiceModal-{{$service->id}}"
        data-login-url="{{ route('frontend.login') }}"
        data-check-login-url="{{ route('frontend.check.login') }}"
        data-service-id="{{ $service->id }}">
                {{ __('frontend::static.services.book_now')}}<span class="spinner-border spinner-border-sm" style="display: none;"></span>
                </button>

                <div class="provider-detail mt-4">
                    <label class="mb-3">
                    {{ __('frontend::static.services.provider_details')}}
                    </label>
                    <div class="provider-content">
                        <div class="profile-bg"></div>
                        <div class="profile">
                            <a href="{{route('frontend.provider.details', ['slug' => $service?->user?->slug])}}"> 
                                <img src="{{ $service?->user?->media?->first()?->original_url ?? asset('frontend/images/user.png') }}" alt="{{ $service?->user->name }}" class="img">
                            </a>
                            <a href="{{route('frontend.provider.details', ['slug' => $service?->user?->slug])}}"> 
                            <h3 class="mt-2">{{ $service?->user->name }} </h3>
                            </a>
                        </div>
                        <div class="profile-detail">
                            <ul>
                                <li>
                                    <label for="email">{{ __('frontend::static.services.email')}}</label>
                                    <span>{{ $service?->user->email }}</span>
                                </li>
                                <li>
                                    <label for="contact">{{ __('frontend::static.services.contact_no')}}</label>
                                    <span>+{{ $service?->user?->code }} {{ $service?->user?->phone }}</span>
                                </li>
                                @if ($service?->user->known_languages && count($service?->user->known_languages))
                                <li>
                                    <label for="language">{{ __('frontend::static.services.known_language')}}</label>
                                    <span>{{ implode($service?->user->known_languages) }}</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                        <div class="success-light-badge badge">
                            <span>{{ $service?->user->served }} {{ __('frontend::static.services.service_delivered')}}</span>
                        </div>
                        @if($service?->user?->experience_duration)
                        <div class="danger-light-badge badge">
                            <span>{{$service?->user?->experience_duration}} {{$service?->user?->experience_interval}} {{ __('frontend::static.services.of_experience')}}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 content-b-space">
                <div class="title">
                    <h2>{{ __('frontend::static.services.featured_services')}}</h2>
                    <a class="view-all" href="{{route('frontend.service.index')}}">
                    {{ __('frontend::static.services.view_all')}}
                        <i class="iconsax" icon-name="arrow-right"></i>
                    </a>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-4 row-cols-3xl-4 g-4">
                    @foreach ($recentService as $service)
                    <div class="col">
                        <div class="card ratio3_2">
                            @if($service->discount)
                            <div class="discount-tag">{{ $service->discount }}%</div>
                            @endif
                            @auth
                            <div class="like-icon" id="favouriteDiv" data-service-id="{{ $service?->id }}">
                                <img class="img-fluid icon outline-icon" src="{{ asset('frontend/images/svg/heart-outline.svg')}}"
                                    alt="whishlist">
                                <img class="img-fluid icon fill-icon" src="{{ asset('frontend/images/svg/heart-fill.svg')}}" alt="wishlisted">
                            </div>
                            @endauth
                            <div class="overflow-hidden b-r-5">
                                <a href="{{ route('frontend.service.details', $service?->slug) }}" class="card-img">
                                    <img src="{{ $service?->web_img_thumb_url }}" alt="{{ $service?->title }}" class="bg-img">
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="service-title">
                                    @if($service?->title)
                                    <h4><a href="{{ route('frontend.service.details', $service?->slug) }}">{{ $service?->title }}</a>
                                    </h4>
                                    @endif
                                    @if($service->price || $service->service_rate)
                                    <div class="d-flex align-items-center gap-1">
                                        @if($service->price && $service->service_rate)
                                        <del>{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($service->price) }}</del>
                                        <small>{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($service->service_rate) }}</small>
                                        @else
                                        <small>{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($service->price) }}</small>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                <div class="service-detail mt-1">
                                    <ul>
                                        @if($service?->duration)
                                        <li class="time">
                                            <i class="iconsax" icon-name="clock"></i>
                                            <span>{{ $service?->duration }} {{ $service?->duration_unit }}</span>
                                        </li>
                                        @endif
                                        <li>{{ __('frontend::static.services.min')}} {{ $service?->required_servicemen }} {{ __('frontend::static.services.servicemen_required')}}</li>
                                    </ul>
                                    <p>{{ $service?->description }}</p>
                                </div>
                            </div>
                            <div class="card-footer border-top-0">
                                <div class="footer-detail">
                                    <img src="{{ $service?->user?->media?->first()->getURL() }}" alt="feature" class="img-fluid">
                                    <div>
                                        <p>{{ $service?->user?->name }}</p>
                                        <div class="rate">
                                            <img src="{{ asset('frontend/images/svg/star.svg') }}" alt="star" class="img-fluid star">
                                            <small>{{ $service?->user?->review_ratings ?? 'Unrated' }}</small>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn book-now-btn btn-solid w-auto" id="bookNowButton"
        data-bs-toggle="modal" 
        data-bs-target="#bookServiceModal-{{ $service->id }}"
        data-login-url="{{ route('frontend.login') }}"
        data-check-login-url="{{ route('frontend.check.login') }}"
        data-service-id="{{ $service->id }}">
    {{ __('frontend::static.services.book_now') }}
    <span class="spinner-border spinner-border-sm" style="display: none;"></span>
</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Service List Section End -->
@forelse($recentService as $service)
@includeIf('frontend.inc.modal',['service' => $service])
@empty
@endforelse
@endsection

@push('js')
<script>
    "use strict";
    $(function() {
        const input = $('#quantityInput');
        $('#add').on('click', () => {
            let val = +input.val();
            if (val < +input.attr('max')) input.val(val + 1);
        });
        $('#minus').on('click', () => {
            let val = +input.val();
            if (val > +input.attr('min')) input.val(val - 1);
        });
    });
</script>
@auth
<script src="{{ asset('frontend/js/custom-wishlist.js') }}"></script>
@endauth
@endpush