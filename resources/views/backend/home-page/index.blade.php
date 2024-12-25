@extends('backend.layouts.master')

@section('title', __('static.home_pages.home_page'))

@section('content')
@use('App\Models\Settings')
@use('App\Models\Service')
@use('App\Models\Category')
@use('App\Models\Blog')
@use('App\Models\ServicePackage')
@use('app\Helpers\Helpers')

@php
$services = Service::whereNull('deleted_at')?->pluck('title', 'id');
$categories = Category::where('category_type', 'service')->whereNull('deleted_at')?->pluck('title', 'id');
$providers = Helpers::getProviders()?->pluck('name', 'id');
$blogs = Blog::whereNull('deleted_at')?->pluck('title', 'id');
$service_packages = ServicePackage::whereNull('deleted_at')?->pluck('title', 'id');
$bannerServices = Service::whereNull('deleted_at')?->where('status', true)?->get(['id', 'title']);
$bannerCategories = Category::whereNull('deleted_at')?->
where('category_type', 'service')?->
where('status', true)?->get(['id', 'title']);
$bannerServicePackages = ServicePackage::whereNull('deleted_at')?->where('status', true)?->get(['id', 'title']);
@endphp


<div class="card tab2-card">
    <div class="card-header">
        <h5>{{ __('static.home_pages.home_page')}}</h5>
    </div>
    <div class="card-body">
        <div class="vertical-tabs">
            <div class="row g-xl-4 g-3">
                <div class="col-xxl-3 col-xl-4 col-12">
                    <div class="nav nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-tabContent" data-bs-toggle="pill" href="#home_banner" type="button" role="tab" aria-controls="home_banner" aria-selected="true">
                            <i class="ri-home-line"></i>Home Banner
                        </a>
                        <a class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" href="#categories_icon_list" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                            <i class="ri-menu-search-line"></i>Categories Icon List
                        </a>
                        <a class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" href="#value_banners" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                            <i class="ri-image-line"></i>Value Banners
                        </a>
                        <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#service_list_1" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                            <i class="ri-server-line"></i> Service List 1
                        </a>
                        <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#download" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                            <i class="ri-download-2-line"></i>Download
                        </a>
                        <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#providers_list" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                            <i class="ri-user-line"></i>Providers List
                        </a>
                        <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#service_packages_list" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                            <i class="ri-server-line"></i>Service Packages List
                        </a>
                        <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#blogs_list" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                            <i class="ri-blogger-line"></i>Blogs List
                        </a>
                        <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#become_a_provider" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                            <i class="ri-user-line"></i>Become Provider
                        </a>
                        <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#testimonial" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                            <i class="ri-terminal-box-line"></i>Testimonial
                        </a>
                        <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#news_letter" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                            <i class="ri-news-line"></i>News Letter
                        </a>
                    </div>
                </div>
                <div class="col-xxl-7 col-xl-8 col-12">
                    <form method="POST" class="needs-validation user-add h-100" id="homePageForm" action="{{ route('backend.update.home_page',$homePageId) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="tab-content w-100" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="home_banner">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="home_banner[title]">{{ __('Title') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="home_banner[title]" name="home_banner[title]" value="{{ $homePage['home_banner']['title'] ?? old('home_banner[title]') }}" placeholder="{{ _('Enter Home Banner Title') }}">
                                            @error('home_banner[title]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="home_banner[animate_text]">{{ __('Animate text') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="home_banner[animate_text]" name="home_banner[animate_text]" value="{{ $homePage['home_banner']['animate_text'] ?? old('home_banner[animate_text]') }}" placeholder="{{ _('Enter Home banner animated text') }}">
                                            @error('home_banner[animate_text]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="address" class="col-md-2">{{ __('Description') }}</label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="4" name="home_banner[description]" id="home_banner[description]"
                                                placeholder="{{ __('Enter Description') }}" cols="50">{{ $homePage['home_banner']['description'] ?? old('home_banner[description]') }} </textarea>
                                            @error('home_banner[description]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="home_banner[search_enable]">{{ __('Search Box') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($homePage['home_banner']['search_enable']))
                                                    <input class="form-control" type="hidden" name="home_banner[search_enable]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="home_banner[search_enable]" value="1" {{ $homePage['home_banner']['search_enable'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden" name="home_banner[search_enable]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="home_banner[search_enable]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="service_ids">{{ __('Services') }} </label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control" id="home_banner[service_ids][]" search="true"
                                                name="home_banner[service_ids][]" data-placeholder="{{ __('Select Services') }}" multiple>
                                                <option></option>
                                                @foreach ($services as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (is_array(old('service_ids')) && in_array($key, old('service_ids'))) || (isset($homePage['home_banner']['service_ids']) && in_array($key, $homePage['home_banner']['service_ids'])) ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('service_ids')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="home_banner[status]">{{ __('Status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($homePage['home_banner']['status']))
                                                    <input class="form-control" type="hidden" name="home_banner[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="home_banner[status]" value="1" {{ $homePage['home_banner']['status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden" name="home_banner[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="home_banner[status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="categories_icon_list" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="1">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="categories_icon_list[title]">{{ __('Title') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="categories_icon_list[title]" name="categories_icon_list[title]" value="{{ $homePage['categories_icon_list']['title'] ?? old('categories_icon_list[title]') }}" placeholder="{{ _('Enter Category Title') }}">
                                            @error('categories_icon_list[title]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="category_ids">{{ __('Categories') }} </label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control" id="categories_icon_list[category_ids][]" search="true"
                                                name="categories_icon_list[category_ids][]" data-placeholder="{{ __('Select Categories') }}" multiple>
                                                <option></option>
                                                @foreach ($categories as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (is_array(old('category_ids')) && in_array($key, old('category_ids'))) || (isset($homePage['categories_icon_list']['category_ids']) && in_array($key, $homePage['categories_icon_list']['category_ids'])) ? 'selected' : ''  }}>
                                                    {{ $value }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('category_ids')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="categories_icon_list[status]">{{ __('Status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($homePage['categories_icon_list']['status']))
                                                    <input class="form-control" type="hidden" name="categories_icon_list[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="categories_icon_list[status]" value="1" {{ $homePage['categories_icon_list']['status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden" name="categories_icon_list[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="categories_icon_list[status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="value_banners" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="2">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="value_banners[title]">{{ __('Title') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="value_banners[title]" name="value_banners[title]" value="{{ $homePage['value_banners']['title'] ?? old('value_banners[title]') }}" placeholder="{{ _('Enter Value Banner Title') }}">
                                            @error('value_banners[title]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-md-2" for="value_banners[status]">{{ __('Status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($homePage['value_banners']['status']))
                                                    <input class="form-control" type="hidden" name="value_banners[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="value_banners[status]" value="1" {{ $homePage['value_banners']['status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden" name="value_banners[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="value_banners[status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion theme-accordion" id="valueBannersAccordion">
                                    @foreach($homePage['value_banners']['banners'] ?? [] as $index => $banner)
                                    @include('backend.home-page.banners', ['index' => $index, 'banner' => $banner])
                                    @endforeach
                                </div>

                                <button type="button" id="addBanner" class="btn btn-primary mt-3">Add Banner</button>
                                <template id="bannerTemplate">
                                    @include('backend.home-page.banners', ['index' => '__INDEX__', 'banner' => null])
                                </template>
                            </div>

                            <div class="tab-pane fade" id="service_list_1" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="3">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="service_list_1[title]">{{ __('Title') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="service_list_1[title]" name="service_list_1[title]" value="{{ $homePage['service_list_1']['title'] ?? old('service_list_1[title]') }}" placeholder="{{ _('Enter Title') }}">
                                            @error('service_list_1[title]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="service_ids">{{ __('Services') }} </label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control" id="service_list_1[service_ids][]" search="true"
                                                name="service_list_1[service_ids][]" data-placeholder="{{ __('Select Services') }}" multiple>
                                                <option></option>
                                                @foreach ($services as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (is_array(old('service_ids')) && in_array($key, old('service_ids'))) || (isset($homePage['service_list_1']['service_ids']) && in_array($key, $homePage['service_list_1']['service_ids'])) ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('service_ids')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="service_list_1[status]">{{ __('Status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($homePage['service_list_1']['status']))
                                                    <input class="form-control" type="hidden" name="service_list_1[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="service_list_1[status]" value="1" {{ $homePage['service_list_1']['status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden" name="service_list_1[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="service_list_1[status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="download" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="4">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="download[status]">{{ __('Status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($homePage['download']['status']))
                                                    <input class="form-control" type="hidden" name="download[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="download[status]" value="1" {{ $homePage['download']['status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden" name="download[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="download[status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="image"
                                            class="col-md-2">{{ __('Image / GIF ') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file" id="download[image_url]"
                                                name="download[image_url]">
                                            @error('download[image_url]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    @isset($homePage['download']['image_url'])
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">
                                                <div class="image-list">
                                                    <div class="image-list-detail">
                                                        <div class="position-relative">
                                                            <img src="{{ asset($homePage['download']['image_url']) }}"
                                                                id="{{ $homePage['download']['image_url'] }}"
                                                                alt="Header Logo" class="image-list-item">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endisset

                                    <div class="form-group row">
                                        <label class="col-md-2" for="download[title]">{{ __('Title') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="download[title]" name="download[title]" value="{{ $homePage['download']['title'] ?? old('download[title]') }}" placeholder="{{ _('Enter Title') }}">
                                            @error('download[title]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="address" class="col-md-2">{{ __('Description') }}<span> </span></label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="4" name="download[description]" id="download[description]"
                                                placeholder="{{ __('Enter Description') }}" cols="50">{{ $homePage['download']['description'] ?? old('download[description]') }} </textarea>
                                            @error('download[description]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="providers_list" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="6">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="providers_list[title]">{{ __('Title') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="providers_list[title]" name="providers_list[title]" value="{{ $homePage['providers_list']['title'] ?? old('providers_list[title]') }}" placeholder="{{ _('Enter Title') }}">
                                            @error('providers_list[title]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="provider_ids">{{ __('Providers') }} </label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control" id="providers_list[provider_ids][]" search="true"
                                                name="providers_list[provider_ids][]" data-placeholder="{{ __('Select Providers') }}" multiple>
                                                <option></option>
                                                @foreach ($providers as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (is_array(old('provider_ids')) && in_array($key, old('provider_ids'))) || (isset($homePage['providers_list']['provider_ids']) && in_array($key, $homePage['providers_list']['provider_ids'])) ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('provider_ids')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="providers_list[status]">{{ __('Status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($homePage['providers_list']['status']))
                                                    <input class="form-control" type="hidden" name="providers_list[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="providers_list[status]" value="1" {{ $homePage['providers_list']['status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden" name="providers_list[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="providers_list[status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="service_packages_list" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="6">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="service_packages_list[title]">{{ __('Title') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="service_packages_list[title]" name="service_packages_list[title]" value="{{ $homePage['service_packages_list']['title'] ?? old('service_packages_list[title]') }}" placeholder="{{ _('Enter Title') }}">
                                            @error('service_packages_list[title]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="service_packages_ids">{{ __('Service Packages') }} </label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control" id="service_packages_list[service_packages_ids][]" search="true"
                                                name="service_packages_list[service_packages_ids][]" data-placeholder="{{ __('Select Service Packages') }}" multiple>
                                                <option></option>
                                                @foreach ($service_packages as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (is_array(old('provider_ids')) && in_array($key, old('provider_ids'))) || (isset($homePage['service_packages_list']['service_packages_ids']) && in_array($key, $homePage['service_packages_list']['service_packages_ids'])) ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('service_packages_ids')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="service_packages_list[status]">{{ __('Status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($homePage['service_packages_list']['status']))
                                                    <input class="form-control" type="hidden" name="service_packages_list[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="service_packages_list[status]" value="1" {{ $homePage['service_packages_list']['status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden" name="service_packages_list[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="service_packages_list[status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="blogs_list" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="6">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="blogs_list[title]">{{ __('Title') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="blogs_list[title]" name="blogs_list[title]" value="{{ $homePage['blogs_list']['title'] ?? old('blogs_list[title]') }}" placeholder="{{ _('Enter Title') }}">
                                            @error('blogs_list[title]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="address" class="col-md-2">{{ __('Description') }}<span> </span></label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="4" name="blogs_list[description]" id="blogs_list[description]"
                                                placeholder="{{ __('Enter Description') }}" cols="50">{{ $homePage['blogs_list']['description'] ?? old('blogs_list[description]') }} </textarea>
                                            @error('blogs_list[description]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="blog_ids">{{ __('Blogs') }} </label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control" id="blogs_list[blog_ids][]" search="true"
                                                name="blogs_list[blog_ids][]" data-placeholder="{{ __('Select Blogs') }}" multiple>
                                                <option></option>
                                                @foreach ($blogs as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (is_array(old('blog_ids')) && in_array($key, old('blog_ids'))) || (isset($homePage['blogs_list']['blog_ids']) && in_array($key, $homePage['blogs_list']['blog_ids'])) ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('blog_ids')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="blogs_list[status]">{{ __('Status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($homePage['blogs_list']['status']))
                                                    <input class="form-control" type="hidden" name="blogs_list[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="blogs_list[status]" value="1" {{ $homePage['blogs_list']['status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden" name="blogs_list[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="blogs_list[status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="become_a_provider" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="6">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="become_a_provider[status]">{{ __('Status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($homePage['become_a_provider']['status']))
                                                    <input class="form-control" type="hidden" name="become_a_provider[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="become_a_provider[status]" value="1" {{ $homePage['become_a_provider']['status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden" name="become_a_provider[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="become_a_provider[status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="image"
                                            class="col-md-2">{{ __('Image') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file" id="become_a_provider[image_url]"
                                                name="become_a_provider[image_url]">
                                            @error('become_a_provider[image_url]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <span
                                                class="help-text">{{ __('static.theme_options.upload_logo_image_size') }}</span>
                                        </div>
                                    </div>
                                    @isset($homePage['become_a_provider']['image_url'])
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">
                                                <div class="image-list">
                                                    <div class="image-list-detail">
                                                        <div class="position-relative">
                                                            <img src="{{ asset($homePage['become_a_provider']['image_url']) }}"
                                                                id="{{ $homePage['become_a_provider']['image_url'] }}"
                                                                alt="Float image" class="image-list-item">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endisset

                                    <div class="form-group row">
                                        <label for="image"
                                            class="col-md-2">{{ __('Float Image 1') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file" id="become_a_provider[float_image_1_url]"
                                                name="become_a_provider[float_image_1_url]">
                                            @error('become_a_provider[float_image_1_url]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <span
                                                class="help-text">{{ __('static.theme_options.upload_logo_image_size') }}</span>
                                        </div>
                                    </div>
                                    @isset($homePage['become_a_provider']['float_image_1_url'])
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">
                                                <div class="image-list">
                                                    <div class="image-list-detail">
                                                        <div class="position-relative">
                                                            <img src="{{  asset($homePage['become_a_provider']['float_image_1_url']) }}"
                                                                id="{{ $homePage['become_a_provider']['float_image_1_url'] }}"
                                                                alt="Become a image" class="image-list-item">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endisset

                                    <div class="form-group row">
                                        <label for="image"
                                            class="col-md-2">{{ __('Float Image 2') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file" id="become_a_provider[float_image_2_url]"
                                                 name="become_a_provider[float_image_2_url]">
                                            @error('become_a_provider[float_image_2_url]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <span
                                                class="help-text">{{ __('static.theme_options.upload_logo_image_size') }}</span>
                                        </div>
                                    </div>
                                    @isset($homePage['become_a_provider']['float_image_2_url'])
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">
                                                <div class="image-list">
                                                    <div class="image-list-detail">
                                                        <div class="position-relative">
                                                            <img src="{{  asset($homePage['become_a_provider']['float_image_2_url']) }}"
                                                                id="{{ $homePage['become_a_provider']['float_image_2_url'] }}"
                                                                alt="Become a image" class="image-list-item">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endisset
                                    <div class="form-group row">
                                        <label class="col-md-2" for="become_a_provider[title]">{{ __('Title') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="become_a_provider[title]" name="become_a_provider[title]" value="{{ $homePage['become_a_provider']['title'] ?? old('become_a_provider[title]') }}" placeholder="{{ _('Enter Title') }}">
                                            @error('become_a_provider[title]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="address" class="col-md-2">{{ __('Description') }}<span> </span></label>
                                        <div class="col-md-10">
                                            <textarea class="form-control" rows="4" name="become_a_provider[description]" id="become_a_provider[description]"
                                                placeholder="{{ __('Enter Description') }}" cols="50">{{ $homePage['become_a_provider']['description'] ?? old('become_a_provider[description]') }} </textarea>
                                            @error('become_a_provider[description]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="become_a_provider[button_text]">{{ __('Button Text') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="become_a_provider[button_text]" name="become_a_provider[button_text]" value="{{ $homePage['become_a_provider']['button_text'] ?? old('become_a_provider[button_text]') }}" placeholder="{{ _('Enter Button Text') }}">
                                            @error('become_a_provider[button_text]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="become_a_provider[button_url]">{{ __('Button link') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="become_a_provider[button_url]" name="become_a_provider[button_url]" value="{{ $homePage['become_a_provider']['button_url'] ?? old('become_a_provider[button_url]') }}" placeholder="{{ _('Enter Button Text') }}">
                                            @error('become_a_provider[button_url]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="testimonial" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="6">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="testimonial[title]">{{ __('Title') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="testimonial[title]" name="testimonial[title]" value="{{ $homePage['testimonial']['title'] ?? old('testimonial[title]') }}" placeholder="{{ _('Enter Title') }}">
                                            @error('testimonial[title]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="testimonial[status]">{{ __('Status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($homePage['testimonial']['status']))
                                                    <input class="form-control" type="hidden" name="testimonial[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="testimonial[status]" value="1" {{ $homePage['testimonial']['status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden" name="testimonial[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="testimonial[status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="news_letter" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="6">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2" for="news_letter[title]">{{ __('Title') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="news_letter[title]" name="news_letter[title]" value="{{ $homePage['news_letter']['title'] ?? old('news_letter[title]') }}" placeholder="{{ _('Enter Title') }}">
                                            @error('news_letter[title]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="news_letter[sub_title]">{{ __('Sub Title') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="news_letter[sub_title]" name="news_letter[sub_title]" value="{{ $homePage['news_letter']['sub_title'] ?? old('news_letter[sub_title]') }}" placeholder="{{ _('Enter Sub Title') }}">
                                            @error('news_letter[sub_title]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2" for="news_letter[button_text]">{{ __('Button  Text') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="news_letter[button_text]" name="news_letter[button_text]" value="{{ $homePage['news_letter']['button_text'] ?? old('news_letter[button_text]') }}" placeholder="{{ _('Enter Button Text') }}">
                                            @error('news_letter[button_text]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="image"
                                            class="col-md-2">{{ __('Image') }}</label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file" id="news_letter[bg_image_url]"
                                                 name="news_letter[bg_image_url]">
                                            @error('news_letter[bg_image_url]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <span
                                                class="help-text">{{ __('static.theme_options.upload_logo_image_size') }}</span>
                                        </div>
                                    </div>
                                    @isset($homePage['news_letter']['bg_image_url'])
                                    
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">
                                                <div class="image-list">
                                                    <div class="image-list-detail">
                                                        <div class="position-relative">
                                                            <img src="{{ asset($homePage['news_letter']['bg_image_url'])     }}"
                                                                id="{{ $homePage['news_letter']['bg_image_url'] }}"
                                                                alt="Float image" class="image-list-item">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endisset


                                    <div class="form-group row">
                                        <label class="col-md-2" for="news_letter[status]">{{ __('Status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($homePage['news_letter']['status']))
                                                    <input class="form-control" type="hidden" name="news_letter[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="news_letter[status]" value="1" {{ $homePage['news_letter']['status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden" name="news_letter[status]" value="0">
                                                    <input class="form-check-input" type="checkbox" name="news_letter[status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="button-box">
                                <button type="submit" class="btn btn-primary spinner-btn">{{ __('static.save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@push('js')
<script src="{{ asset('admin/js/password-hide-show.js') }}"></script>
<script>
    $(document).ready(function() {

        "use strict";

        let bannerIndex = {{count($homePage['value_banners']['banners'] ?? [])}};

        // Add a new banner
        $('#addBanner').click(function() {
            const template = $('#bannerTemplate').html().replace(/__INDEX__/g, bannerIndex);
            $('#valueBannersAccordion').append(template);
            bannerIndex++;
        });

        // Remove a banner
        $(document).on('click', '.remove-banner', function() {
            $(this).closest('.accordion-item').remove();
        });

        function updateBannerFields(index) {
            var redirectType = $('#redirectType-' + index).val();
            var dynamicSelect = $('#dynamicSelect-' + index);
            var externalUrlField = $('#externalUrl-' + index);
            var dynamicSelectInput = $('#dynamicSelectInput-' + index);
            var buttonUrl = $('input[name="value_banners[banners][' + index + '][button_url]"]');
            var dynamicLabel = $('#dynamicLabel-' + index);

            // Hide both dynamic select and external URL initially
            dynamicSelect.hide();
            externalUrlField.hide();
            dynamicSelectInput.empty();

            if (redirectType === 'service') {
                dynamicSelect.show();



                @foreach($bannerServices as $service)
                dynamicSelectInput.append('<option value="{{ $service->id }}" {{ isset($banner['
                    redirect_id ']) && $banner['
                    redirect_id '] == $service->id ? '
                    selected ' : '
                    ' }}>{{ $service->title }}</option>');
                @endforeach


            } else if (redirectType === 'package') {
                dynamicSelect.show();
                dynamicSelectInput.append('<option value="">Select Service Package</option>');

                @foreach($bannerServicePackages as $servicePackage)
                dynamicSelectInput.append('<option value="{{ $servicePackage->id }}" {{ isset($banner['
                    redirect_id ']) && $banner['
                    redirect_id '] == $servicePackage->id ? '
                    selected ' : '
                    ' }}>{{ $servicePackage->title }}</option>');
                @endforeach



            } else if (redirectType === 'external_url') {
                externalUrlField.show();
            }

            dynamicSelectInput.select2();
        }

        @foreach($homePage['value_banners']['banners'] as $index => $banner)
        updateBannerFields({
            {
                $index
            }
        });
        @endforeach

        $('.redirect-type').on('change', function() {
            var index = $(this).attr('id').split('-')[1];
            updateBannerFields(index);
        });

        // Handle the remove banner button
        $('.remove-banner').on('click', function() {
            $(this).closest('.accordion-item').remove();
        });
    });
</script>
@endpush
