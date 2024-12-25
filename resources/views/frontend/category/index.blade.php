@extends('frontend.layout.master')

@section('title', __('frontend::static.categories.categories'))

@section('breadcrumb')
<nav class="breadcrumb breadcrumb-icon">
    <a class="breadcrumb-item" href="{{url('/')}}">{{__('frontend::static.categories.home')}}</a>
    <span class="breadcrumb-item active">{{__('frontend::static.categories.categories')}}</span>
</nav>
@endsection

@php
    $categories = $categories->paginate($themeOptions['pagination']['categories_per_page'] ?? null)
    @endphp
@section('content')
@use('App\Enums\ServiceTypeEnum')
<section class=" pt-0 content-b-space">
    @forelse ($categories as $category)
    @if (count($category->services))
    <!-- Category Section Start -->
    <section class="salon-section content-t-space2">
        <div class="container-fluid-lg">
            <div class="accordion categories-accordion" id="salon-{{ $category->id }}">
                <div class="accordion-item">
                    <div class="accordion-header" id="salonItem">
                        <div class="title w-100" data-bs-toggle="collapse"
                                data-bs-target="#collapseSalon-{{ $category->id }}" aria-expanded="true"
                                aria-controls="collapseSalon">
                            <h2 title="">{{ $category?->title }}</h2>
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseSalon-{{ $category->id }}" aria-expanded="true"
                                aria-controls="collapseSalon">
                            </button>
                        </div>
                    </div>
                    <div id="collapseSalon-{{ $category->id }}" class="accordion-collapse collapse show"
                        aria-labelledby="collapseSalon" data-bs-parent="#salon-{{ $category->id }}">
                        <div class="accordion-body">
                            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-sm-4 g-3 ratio_94">
                                @forelse ($category->services?->whereNull('parent_id')?->where('type', ServiceTypeEnum::FIXED) as $service)
                                    <div class="col">
                                        <a href="{{route('frontend.service.details', ['slug' => $service?->slug])}}"
                                        class="category-img"><img src="{{ $service?->web_img_thumb_url }}"
                                        alt="{{ $service?->title }}" class="bg-img lozad"></a>
                                        <a href="{{route('frontend.service.details', ['slug' => $service?->slug])}}"
                                            class="category-img"><span title="{{ $service?->title }}" class="category-span">{{ $service?->title }}</span></a>
                                    </div>
                                @empty
                                    <div class="no-data-found">
                                        <p>{{__('frontend::static.categories.services_not_found')}}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Category Section End -->
    @endif
    @empty
    <div class="no-data-found category-no-data">
        <img class="img-fluid no-data-img" src="{{ asset('frontend/images/no-data.svg')}}" alt="">
        <p>{{__('frontend::static.categories.categories_not_found')}}</p>
    </div>
    @endforelse
    @if(count($categories ?? []))
        @if($categories?->lastPage() > 1)
            <div class="row">
                <div class="col-12">
                    <div class="pagination-main">
                        <ul class="pagination">
                            {!! $categories->links() !!}
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    @endif
</section>
@endsection
