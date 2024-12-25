@use('app\Helpers\Helpers')
@extends('frontend.layout.master')

@section('title', __('frontend::static.servicePackages.servicePackages'))

@section('breadcrumb')
<nav class="breadcrumb breadcrumb-icon">
  <a class="breadcrumb-item" href="{{url('/')}}">{{ __('frontend::static.servicePackages.home')}}</a>
  <span class="breadcrumb-item active">{{ __('frontend::static.servicePackages.servicePackages')}}</span>
</nav>
@endsection

@section('content')
<!-- Service Packages List Section Start -->
<section class="service-package-section section-b-space">
  <div class="container-fluid-lg">
    <div class="service-package-content">
      <div class="row g-sm-4 g-3">
        @forelse ($servicePackages as $servicePackage)
        <div class="col-xxl-3 col-lg-4 col-sm-6">
          <a href="{{ route('frontend.service-package.details', $servicePackage?->slug) }}" class="service-bg-{{ $servicePackage?->bg_color ?? 'primary' }} service-bg d-block">
            <img src="{{ asset('frontend/images/svg/2.svg') }}"
              alt="{{ $servicePackage?->name }}" class="img-fluid service-1">
            <div class="service-detail">
              <div class="service-icon">
                <img src="{{ Helpers::isFileExistsFromURL($servicePackage?->media?->first()?->getUrl(), true) }}"
                  alt="{{ $servicePackage?->services?->first()?->categories?->first()?->name }}"
                  class="img-fluid">
              </div>
              <h3>{{ $servicePackage?->title }}</h3>
              <div class="price">
                @php
                $salePrice = Helpers::getServicePackageSalePrice($servicePackage?->id);
                @endphp
                <span
                  class="text-white">{{ Helpers::getDefaultCurrencySymbol() }}{{ Helpers::covertDefaultExchangeRate($salePrice) }}</span>
                <span>
                  <i class="iconsax" icon-name="arrow-right"></i>
                </span>
              </div>
            </div>
          </a>
        </div>
        @empty
        <div class="no-data-found">
          <img class="img-fluid no-data-img" src="{{ asset('frontend/images/no-data.svg')}}" alt="">
          <p>{{ __('frontend::static.servicePackages.not_found')}}</p>
        </div>
        @endforelse
      </div>
    </div>
    @if($servicePackages ?? [])
    @if($servicePackages?->lastPage() > 1)
    <div class="pagination-main pt-0 ">
      <ul class="pagination-box">
        {!! $servicePackages->links() !!}
      </ul>
    </div>
    @endif
    @endif
</section>
<!-- Service Package List Section End -->
@endsection
