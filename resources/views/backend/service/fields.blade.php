@use('app\Helpers\Helpers')
@use('App\Enums\ServiceTypeEnum')
@php
    if (isset($service->destination_location['country_id']) || old('country_id')) {
        $states = \App\Models\State::where(
            'country_id',
            old('country_id', @$service->destination_location['country_id']),
        )->get();
    } else {
        $states = [];
    }
@endphp
<ul class="nav nav-tabs tab-coupon" id="servicetab" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ session('active_tab') != null ? '' : 'show active' }}" id="general-tab" data-bs-toggle="tab"
            href="#general" role="tab" aria-controls="general" aria-selected="true" data-original-title=""
            title="" data-tab="0">
            <i data-feather="settings"></i>{{ __('static.general') }}</a>
    </li>

    <li class="nav-item" id="address_tab">
        <a class="nav-link {{ session('active_tab') == 'address_tab' ? 'show active' : '' }}" id="address-tab"
            data-bs-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="true">
            <i data-feather="map-pin"></i> {{ __('static.provider.address') }}
        </a>
    </li>

    <li class="nav-item d-none" id="edit_address_tab">
        <a class="nav-link {{ session('active_tab') == 'edit_address_tab' ? 'show active' : '' }}" id="edit-address-tab"
            data-bs-toggle="tab" href="#edit_address" role="tab" aria-controls="address" aria-selected="true">
            <i data-feather="map-pin"></i> {{ __('static.provider.address') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" id="faq-tab" data-bs-toggle="tab" href="#faq" role="tab" aria-controls="faq"
            aria-selected="true" data-original-title="" title="" data-tab="2">
            <i data-feather="help-circle"></i> {{ __('FAQ\'s') }}</a>
    </li>
</ul>
<div class="tab-content" id="servicetabContent">
    <div class="tab-pane fade {{ session('active_tab') != null ? '' : 'show active' }}" id="general" role="tabpanel"
        aria-labelledby="general-tab">
        <div class="form-group row">
            <label class="col-md-2" for="title">{{ __('static.title') }}<span> *</span></label>
            <div class="col-md-10">
                <input class='form-control' type="text" id="title" name="title"
                    value="{{ isset($service->title) ? $service->title : old('title') }}"
                    placeholder="{{ __('static.service.enter_title') }}">
                @error('title')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="zone_id">{{ __('static.service.zone') }}<span> *</span></label>
            <div class="col-md-10 error-div select-dropdown">
                {{-- @dd("csdxcdx",$selected_zones) --}}
                <select class="select-2 form-control" id="zone_id[]" name="zone_id[]"
                    data-placeholder="{{ __('static.service.select_zone') }}" multiple>
                    <option class="select-placeholder" value=""></option>
                    @foreach ($zones as $key => $option)
                        <option class="option" value="{{ $key }}"
                            @if (isset($selected_zones) && in_array($key, $selected_zones)) selected 
                            @elseif (old('zone_id') && in_array($key, old('zone_id'))) selected @endif>
                            {{ $option }}</option>
                    @endforeach
                </select>
                @error('zone_id[]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-2" for="category_id">{{ __('static.service.category') }}<span> *</span></label>
            <div class="col-md-10 error-div select-dropdown">
                <select id="category_id" class="select-2 form-control categories"
                    data-placeholder="{{ __('static.service.select_categories') }}" search="true" name="category_id[]"
                    multiple>
                    <option value=""></option>
                    {{-- @foreach ($categories as $key => $value) --}}
                        {{-- <option value="{{ $key }}"
                            @if (isset($default_categories) && in_array($key, $default_categories)) selected 
                            @elseif (old('category_id') && in_array($key, old('category_id'))) selected @endif>
                            {{ $value }}
                        </option> --}}
                    {{-- @endforeach --}}
                </select>
                @error('category_id')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="type">{{ __('static.service.type') }}<span> *</span></label>
            <div class="col-md-10 error-div select-dropdown">
                <select class="select-2 form-control" id="type" name="type"
                    data-placeholder="{{ __('static.service.select_type') }}">
                    <option class="select-placeholder" value=""></option>
                    @foreach ([ServiceTypeEnum::FIXED => 'Fixed', ServiceTypeEnum::PROVIDER_SITE => 'Provider Site', ServiceTypeEnum::REMOTELY => 'Remotely'] as $key => $option)
                        <option class="option" value="{{ $key }}"
                            @if (old('type', isset($service) ? $service->type : '') == $key) selected @endif>{{ $option }}</option>
                    @endforeach
                </select>
                @error('type')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        @hasrole('admin')
            <div class="form-group row">
                <label class="col-md-2" for="user_id">{{ __('static.service.provider') }}<span> *</span></label>
                <div class="col-md-10 error-div select-dropdown">
                    <select class="select-2 form-control user-dropdown" id="user_id" name="user_id"
                        data-placeholder="{{ __('static.service.select_provider') }}">
                        <option class="select-placeholder" value=""></option>
                        @foreach ($providers as $key => $option)
                            <option value="{{ $option->id }}" sub-title="{{ $option->email }}"
                                image="{{ $option->getFirstMedia('image')?->getUrl() }}"
                                @if (old('user_id', isset($service) ? $service->user_id : '') == $option->id) selected @endif>
                                {{ $option->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        @endhasrole
        @hasrole('provider')
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" id="user_id">
        @endhasrole
        <div class="form-group row">
            <label class="col-md-2"
                for="required_servicemen">{{ __('static.service.required_servicemen') }}<span>*</span></label>
            <div class="col-md-10">
                <input class='form-control' type="number" min="1" id="required_servicemen"
                    name="required_servicemen"
                    value="{{ isset($service->required_servicemen) ? $service->required_servicemen : old('required_servicemen') }}"
                    placeholder="{{ __('static.service.enter_required_servicemen') }}">
                @error('required_servicemen')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="price">{{ __('static.service.price') }}<span> *</span></label>
            <div class="col-md-10 error-div">
                <div class="input-group mb-3 flex-nowrap">
                    <span
                        class="input-group-text">{{ Helpers::getSettings()['general']['default_currency']->symbol }}</span>
                    <div class="w-100">
                        <input class='form-control' type="number" id="price" name="price" min="1"
                            value="{{ isset($service->price) ? $service->price : old('price') }}"
                            placeholder="{{ __('static.coupon.price') }}">
                        @error('price')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="discount">{{ __('static.service.discount') }}</label>
            <div class="col-md-10 error-div">
                <div class="input-group mb-3 flex-nowrap">
                    <div class="w-100 percent">
                        <input class='form-control' id="discount" type="number" name="discount" min="1"
                            value="{{ $service->discount ?? old('discount') }}"
                            placeholder="{{ __('static.service.enter_discount') }}"
                            oninput="if (value > 100) value = 100; if (value < 0) value = 0;">
                    </div>
                    <span class="input-group-text">%</span>
                    @error('discount')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="service_rate">{{ __('static.service.service_rate') }}</label>
            <div class="col-md-10">
                <input class='form-control' type="number" id="service_rate" name="service_rate"
                    value="{{ isset($service->service_rate) ? $service->service_rate : old('service_rate') }}"
                    placeholder="{{ __('static.service.service_rate') }}" readonly>
                @error('service_rate')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2"
                for="per_serviceman_commission">{{ __('static.service.per_serviceman_commission') }}<span>
                    *</span></label>
            <div class="col-md-10 error-div">
                <div class="input-group mb-3 flex-nowrap">
                    <div class="w-100 percent">
                        <input class='form-control' id="per_serviceman_commission" type="number"
                            name="per_serviceman_commission" min="1"
                            value="{{ $service->per_serviceman_commission ?? old('per_serviceman_commission') }}"
                            placeholder="{{ __('static.service.enter_per_serviceman_commission') }}"
                            oninput="if (value > 100) value = 100; if (value < 0) value = 0;">
                    </div>
                    <span class="input-group-text">%</span>
                    @error('per_serviceman_commission')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="tax_id">{{ __('static.service.taxes') }}<span> *</span></label>
            <div class="col-md-10 error-div select-dropdown">
                <select class="select-2 form-control" id="tax_id" name="tax_id"
                    data-placeholder="{{ __('static.service.select_tax') }}">
                    <option class="select-placeholder" value=""></option>
                    @foreach ($taxes as $key => $option)
                        <option class="option" value="{{ $key }}"
                            @if (old('tax_id', isset($service) ? $service->tax_id : '') == $key) selected @endif>
                            {{ $option }}</option>
                    @endforeach
                </select>
                @error('tax_id')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="duration">{{ __('static.service.duration') }}<span> *</span></label>
            <div class="col-md-10">
                <input class="form-control" type="number" min="1" name="duration" id="duration"
                    value="{{ isset($service->duration) ? $service->duration : old('duration') }}"
                    placeholder="{{ __('static.service.enter_duration') }}">
                @error('duration')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="duration_unit">{{ __('static.service.duration_unit') }}<span>
                    *</span></label>
            <div class="col-md-10 error-div select-dropdown">
                <select class="select-2 form-control" id="duration_unit" name="duration_unit"
                    data-placeholder="{{ __('static.service.select_duration_unit') }}">
                    <option class="select-placeholder" value=""></option>
                    @foreach (['hours' => 'Hours', 'minutes' => 'Minutes'] as $key => $option)
                        <option class="option" value="{{ $key }}"
                            @if (old('duration_unit', $service->duration_unit ?? '') === $key) selected @endif>{{ $option }}</option>
                    @endforeach
                </select>
                @error('duration_unit')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="thumbnail" class="col-md-2">{{ __('static.categories.thumbnail') }}<span> *</span></label>
            <div class="col-md-10">
                <input class="form-control" type="file" id="thumbnail"
                    name="thumbnail">
                @error('thumbnail')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        @if (isset($service) && isset($service->getFirstMedia('thumbnail')->original_url))
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-10">
                        <div class="image-list">
                            <div class="image-list-detail">
                                <div class="position-relative">
                                    <img src="{{ $service->getFirstMedia('thumbnail')->original_url }}"
                                        id="{{ $service->getFirstMedia('thumbnail')->id }}" alt="User Image"
                                        class="image-list-item">
                                    <div class="close-icon">
                                        <i data-feather="x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="form-group row">
            <label for="image" class="col-md-2">{{ __('static.categories.image') }}<span> *</span></label>
            <div class="col-md-10">
                <input class="form-control" type="file" id="image"  name="image[]"
                    multiple>
                @error('image')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        @if (isset($service->media) && !$service->media->isEmpty())
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-10">
                        <div class="image-list">
                            @foreach ($service->getMedia('image') as $media)
                                <div class="image-list-detail">
                                    <div class="position-relative">
                                        <img src="{{ $media->original_url }}" id="{{ $media->id }}"
                                            alt="User Image" class="image-list-item">
                                        <div class="close-icon">
                                            <i data-feather="x"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group row">
            <label for="web_thumbnail" class="col-md-2">{{ __('static.categories.web_thumbnail') }}<span>
                    *</span></label>
            <div class="col-md-10">
                <input class="form-control" type="file" id="web_thumbnail" name="web_thumbnail">
                @error('web_thumbnail')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        @if (isset($service) && isset($service->getFirstMedia('web_thumbnail')->original_url))
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-10">
                        <div class="image-list">
                            <div class="image-list-detail">
                                <div class="position-relative">
                                    <img src="{{ $service?->getFirstMedia('web_thumbnail')?->original_url }}"
                                        id="{{ $service->getFirstMedia('web_thumbnail')->id }}" alt="User Image"
                                        class="image-list-item">
                                    <div class="close-icon">
                                        <i data-feather="x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group row">
            <label for="web_images" class="col-md-2">{{ __('static.categories.web_images') }}<span> *</span></label>
            <div class="col-md-10">
                <input class="form-control" type="file" id="web_images" name="web_images[]" multiple>
                @error('web_images')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        @if (isset($service->media) && !$service->media->isEmpty())
            <div class="form-group">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-10">
                        <div class="image-list">
                            @foreach ($service->getMedia('web_images') as $media)
                                <div class="image-list-detail">
                                    <div class="position-relative">
                                        <img src="{{ $media->original_url }}" id="{{ $media->id }}"
                                            alt="User Image" class="image-list-item">
                                        <div class="close-icon">
                                            <i data-feather="x"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group row">
            <label for="description" class="col-md-2">{{ __('static.service.description') }}</label>
            <div class="col-md-10">
                <textarea class="form-control" rows="4" name="description"
                    placeholder="{{ __('static.service.enter_description') }}" cols="50">{{ $service->description ?? old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="image" class="col-md-2">{{ __('static.page.content') }}<span> *</span></label>
            <div class="col-md-10">
                <textarea class="summary-ckeditor" id="content" name="content" cols="65" rows="5">{{ isset($service->content) ? $service->content : old('content') }}</textarea>
                @error('content')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="role">{{ __('static.service.is_random_related_services') }}</label>
            <div class="col-md-10">
                <div class="editor-space">
                    <label class="switch">
                        @if (isset($service))
                            <input class="form-control" type="hidden" name="is_random_related_services"
                                value="0">
                            <input class="form-check-input" id="is_related" type="checkbox"
                                name="is_random_related_services" id="" value="1"
                                {{ $service->is_random_related_services ? 'checked' : '' }}>
                        @else
                            <input class="form-control" type="hidden" name="is_random_related_services"
                                value="0">

                            <input class="form-check-input" id="is_related" type="checkbox"
                                name="is_random_related_services" id="" value="1">
                        @endif
                        <span class="switch-state"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row services" @if (isset($service) && $service->is_random_related_services) style="display:none" @endif>
            <label class="col-md-2" for="service_id">{{ __('static.service.related_services') }} <span>
                    *</span></label>
            <div class="col-md-10 error-div select-dropdown">
                <select id="related_services" class="select-2 form-control user-dropdown" search="true"
                    name="service_id[]" data-placeholder="{{ __('static.service.select_related_services') }}"
                    multiple>
                    <option value=""></option>
                </select>
                @error('service_id')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="is_featured">{{ __('static.service.is_featured') }}</label>
            <div class="col-md-10">
                <div class="editor-space">
                    <label class="switch">
                        @if (isset($service))
                            <input class="form-control" type="hidden" name="is_featured" value="0">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                {{ $service->is_featured ? 'checked' : '' }}>
                        @else
                            <input class="form-control" type="hidden" name="is_featured" value="0">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1">
                        @endif
                        <span class="switch-state"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="status">{{ __('static.status') }}</label>
            <div class="col-md-10">
                <div class="editor-space">
                    <label class="switch">
                        @if (isset($service))
                            <input class="form-control" type="hidden" name="status" value="0">
                            <input class="form-check-input" type="checkbox" name="status" value="1"
                                {{ $service->status ? 'checked' : '' }}>
                        @else
                            <input class="form-control" type="hidden" name="status" value="0">
                            <input class="form-check-input" type="checkbox" name="status" value="1" checked>
                        @endif
                        <span class="switch-state"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="footer">
            <button type="button" class="nextBtn btn btn-primary">{{ __('static.next') }}</button>
        </div>
    </div>
    <div class="tab-pane fade {{ session('active_tab') == 'address_tab' ? 'show active' : '' }}" id="address" role="tabpanel" aria-labelledby="address-tab">
        @if (request()->is('backend/service/create'))
            <div class="form-group row">
                <label class="col-md-2" for="role">{{ __('static.address_category') }}</label>
                <div class="col-md-10">
                    <div class="form-group row d-flex align-items-center gap-sm-4 gap-3 ms-0">
                        <div class="form-check w-auto form-radio">
                            <input type="radio" id="home" name="address_type" value="home"
                                class="form-check-input me-2" checked>
                            <label for="home"
                                class="form-check-label mb-0 cursor-pointer">{{ __('static.home') }}</label>
                        </div>
                        <div class="form-check w-auto form-radio">
                            <input type="radio" id="work" name="address_type" value="work"
                                class="form-check-input me-2">
                            <label for="work"
                                class="form-check-label mb-0 cursor-pointer">{{ __('static.work') }}</label>
                        </div>
                        <div class="form-check w-auto form-radio">
                            <input type="radio" id="other" name="address_type" value="other"
                                class="form-check-input me-2">
                            <label for="other"
                                class="form-check-label mb-0 cursor-pointer">{{ __('static.other') }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2" for="alternative_name">{{ __('static.address.alternative_name') }}</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" name="alternative_name"
                        value="{{ old('alternative_name') }}"
                        placeholder="{{ __('static.address.enter_alternative_name') }}">
                    @error('alternative_name')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2" for="phone">{{ __('static.address.alternative_phone') }}</label>
                <div class="col-md-10">
                    <div class="input-group mb-3 phone-detail">
                        <div class="col-sm-1">
                            <select class="select-2 form-control select-country-code" id="select-country-code"
                                name="alternative_code" data-placeholder="">
                                @php
                                    $default = old('code', 1);
                                @endphp
                                <option value="" selected></option>
                                @foreach (App\Helpers\Helpers::getCountryCodes() as $key => $option)
                                    <option class="option" value="{{ $option->phone_code }}"
                                        data-image="{{ asset('admin/images/flags/' . $option->flag) }}"
                                        @if ($option->phone_code == $default) selected @endif
                                        data-default="old('alternative_code')">
                                        {{ $option->phone_code }}
                                    </option>
                                @endforeach
                            </select>
                            @error('alternative_code')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-sm-11">
                            <input class="form-control" type="number" name="alternative_phone"
                                id="alternative_phone" value="{{ old('alternative_phone') }}" min="1"
                                placeholder="{{ __('static.address.enter_alternative_phone') }}">
                        </div>
                    </div>
                    @error('phone')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2" for="address">{{ __('static.users.address') }}<span> *</span></label>
                <div class="col-md-10">
                    <textarea class="form-control ui-widget autocomplete-google" id="address" rows="4"
                        placeholder="{{ __('static.users.enter_address') }}" name="address" cols="50">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="country" class="col-md-2">{{ __('static.users.country') }}<span> *</span></label>
                <div class="col-md-10 error-div select-dropdown">
                    <select class="select-2 form-control select-country" id="country_id" name="country_id"
                        data-placeholder="{{ __('static.users.select_country') }}">
                        <option class="select-placeholder" value=""></option>
                        @forelse ($countries as $key => $option)
                            <option class="option" value={{ $key }}
                                @if (old('country_id')) @if ($key == old('country_id')) selected @endif
                                @endif>
                                {{ $option }}</option>
                        @empty
                            <option value="" disabled></option>
                        @endforelse
                    </select>
                    @error('country_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="country" class="col-md-2">{{ __('static.users.state') }} <span> *</span></label>
                <div class="col-md-10 error-div select-dropdown">
                    <select class="select-2 form-control select-state" data-default-state-id="{{ old('state_id') }}"
                        id="state_id" name="state_id" data-placeholder="{{ __('static.users.select_state') }}">
                        <option class="select-placeholder" value=""></option>
                        @forelse ($countries as $key => $option)
                            <option class="option" value={{ $key }}
                                @if (old('state_id')) @if ($key == old('state_id')) selected @endif
                                @endif> {{ $option }}</option>
                        @empty
                            <option value="" disabled></option>
                        @endforelse
                    </select>
                    @error('state_id')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2" for="city">{{ __('static.city') }}<span> *</span></label>
                <div class="col-md-10">
                    <input class="form-control" type="text" id="city" name="city"
                        value="{{ old('city') }}" placeholder="{{ __('static.users.enter_city') }}">
                    @error('city')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2" for="area">{{ __('static.area') }}<span> *</span></label>
                <div class="col-md-10">
                    <input class="form-control" type="text" name="area" id="area"
                        value="{{ old('area') }}" placeholder="{{ __('static.users.enter_area') }}">
                    @error('area')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2" for="postal_code">{{ __('static.postal_code') }}<span> *</span></label>
                <div class="col-md-10">
                    <input class="form-control" type="text" name="postal_code" id="postal_code"
                        value="{{ old('postal_code') }}" placeholder="{{ __('static.users.postal_code') }}">
                    @error('postal_code')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <input class="form-control" type="hidden" name="latitude" id="latitude"
                value="{{ $service->destination_location['lat'] ?? old('latitude') }}"
                placeholder="{{ __('static.users.latitude') }}">
            <input class="form-control" type="hidden" name="longitude" id="longitude"
                value="{{ $service->destination_location['lng'] ?? old('longitude') }}"
                placeholder="{{ __('static.users.longitude') }}">
        @else
            <a href="javascript:void(0)" class="add-more" data-bs-toggle="modal" data-bs-target="#addaddress">
                <h5>Address List</h5>
                <div class="add-more-div">
                    <i data-feather="plus"></i>
                </div>
            </a>
            <div class="address-body custom-scrollbar">
                <div class="row g-3  @if ($service->addresses->count() == 0) h-100 @endif">
                    @isset($service->addresses)
                        @forelse ($service->addresses as $address)
                            <div class="col-md-6 service-address-box">
                                <div class="service-address">
                                    <div class="service-add-detail">
                                        <div class="address">
                                            <img class="location-icon"
                                                src="{{ asset('admin/images/svg/location.svg') }}">
                                            <div class="address-detail">
                                                <h4>{{ $address->country->name }}-{{ $address->state->name }}</h4>
                                                <h5>{{ $address->city }}</h5>
                                            </div>
                                        </div>
                                        <div class="action d-flex align-items-center gap-2">
                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                data-bs-target="#editAddress{{ $address->id }}" class="edit-icon">
                                                <i data-feather="edit"></i>
                                            </a>
                                            @if (count($service->addresses) > 1)
                                                <a href="#confirmationModal{{ $address->id }}" data-bs-toggle="modal"
                                                    class="delete-icon">
                                                    <i data-feather="trash-2"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="d-flex flex-column no-data-detail">
                                <img class="mx-auto d-flex" src="{{ asset('admin/images/no-category.png') }}"
                                    alt="no-image">
                                <div class="data-not-found">
                                    <span>Address Not Found</span>
                                </div>
                            </div>
                        @endforelse
                    @endisset
                </div>
            </div>
        @endif
        <div class="footer">
            <button type="button" class="previousBtn btn cancel">{{ __('static.previous') }}</button>
            <button class="nextBtn btn btn-primary" type="button">{{ __('static.next') }}</button>
        </div>
    </div>

    <div class="tab-pane fade {{ session('active_tab') == 'edit_address_tab' ? 'show active' : '' }}"
        id="edit_address" role="tabpanel" aria-labelledby="edit-address-tab">
        <div class="form-group row">
            <label class="col-md-2" for="address">{{ __('static.users.address') }}<span> *</span></label>
            <div class="col-md-10">
                <textarea class="form-control ui-widget autocomplete-google" id="destination[address]" rows="4"
                    placeholder="{{ __('static.users.enter_address') }}" name="destination[address]" cols="50">{{ $service?->destination_location['address'] ?? old('address') }}</textarea>
                @error('destination[address]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label for="country" class="col-md-2">{{ __('static.users.country') }}<span> *</span></label>
            <div class="col-md-10 error-div select-dropdown">
                <select class="select-2 form-control select-country" id="destination[country_id]"
                    name="destination[country_id]" data-placeholder="{{ __('static.users.select_country') }}">
                    <option class="select-placeholder" value=""></option>
                    @forelse ($countries as $key => $option)
                        <option class="option" value={{ $key }}
                            @if (old('country_id', isset($service->destination_location) ? $service?->destination_location['country_id'] : '') ==
                                    $key) selected @endif>
                            {{ $option }}</option>
                    @empty
                        <option value="" disabled></option>
                    @endforelse
                </select>
                @error('destination[country_id]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="country" class="col-md-2">{{ __('static.users.state') }} <span> *</span></label>
            <div class="col-md-10 error-div select-dropdown">
                <select class="select-2 form-control select-state" data-default-state-id="{{ old('state_id') }}"
                    id="destination[state_id]" name="destination[state_id]"
                    data-placeholder="{{ __('static.users.select_state') }}">
                    <option class="select-placeholder" value=""></option>
                    @forelse ($states as $key => $state)
                        <option class="option" value={{ $state->id }}
                            @if (old('state_id', isset($service->destination_location) ? $service->destination_location['state_id'] : '') ==
                                    $state->id) selected @endif> {{ $state->name }}</option>
                    @empty
                        <option value="" disabled></option>
                    @endforelse
                </select>
                @error('destination[state_id]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="city">{{ __('static.city') }}<span> *</span></label>
            <div class="col-md-10">
                <input class="form-control" type="text" id="destination[city]" name="destination[city]"
                    value="{{ $service->destination_location['city'] ?? old('city') }}"
                    placeholder="{{ __('static.users.enter_city') }}">
                @error('destination[city]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="area">{{ __('static.area') }}<span> *</span></label>
            <div class="col-md-10">
                <input class="form-control" type="text" name="destination[area]" id="destination[area]"
                    value="{{ $service->destination_location['area'] ?? old('area') }}"
                    placeholder="{{ __('static.users.enter_area') }}">
                @error('destination[area]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2" for="postal_code">{{ __('static.postal_code') }}<span> *</span></label>
            <div class="col-md-10">
                <input class="form-control" type="text" name="destination[postal_code]"
                    id="destination[postal_code]"
                    value="{{ $service->destination_location['postal_code'] ?? old('postal_code') }}"
                    placeholder="{{ __('static.users.postal_code') }}">
                @error('destination[postal_code]')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <input class="form-control" type="hidden" name="lat" id="lat"
            value="{{ $service->destination_location['lat'] ?? old('latitude') }}"
            placeholder="{{ __('static.users.lat') }}">
        <input class="form-control" type="hidden" name="lng" id="lng"
            value="{{ $service->destination_location['lng'] ?? old('lng') }}"
            placeholder="{{ __('static.users.lng') }}">

        <div class="footer">
            <button type="button" class="nextBtn btn btn-primary">{{ __('static.next') }}</button>
        </div>
    </div>

    <div class="tab-pane fade" id="faq" role="tabpanel" aria-labelledby="faq-tab">
        <div class="faq-container mb-2">
            @if (isset($service) && !$service->faqs->isEmpty())
                @foreach ($service->faqs as $index => $faq)
                    <div class="faqs-structure mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-11 col-sm-10 col-12">
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="faqs[{{ $index }}][question]">{{ __('static.service.question') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text"
                                            name="faqs[{{ $index }}][question]"
                                            id="faqs[{{ $index }}][question]"
                                            placeholder="{{ __('static.service.enter_question') }}"
                                            value="{{ $faq['question'] }}" required>
                                    </div>
                                </div>
                                <input type="hidden" name="faqs[{{ $index }}][id]"
                                    value="{{ $faq['id'] }}">
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="faqs[{{ $index }}][answer]">{{ __('static.service.answer') }}</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="faqs[{{ $index }}][answer]" id="faqs[{{ $index }}][answer]"
                                            placeholder="{{ __('static.service.enter_answer') }}" cols="30" rows="5">{{ $faq['answer'] }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-2 col-12">
                                <div class="form-group row">
                                    <!-- <label
                                        class="col-12 opacity-0 d-sm-flex d-none">{{ __('static.service.action') }}</label> -->
                                    <div class="col-12">
                                        <div class="remove-faq mt-4">
                                            <i data-feather="trash-2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="faqs-structure mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-11 col-sm-10 col-12">
                            <div class="form-group row">
                                <label class="col-md-2"
                                    for="faqs[0][question]">{{ __('static.service.question') }}</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="faqs[0][question]"
                                        id="faqs[0][question]" value="{{ old('faqs[0][question]') }}"
                                        placeholder="{{ __('static.service.enter_question') }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2"
                                    for="faqs[0][answer]">{{ __('static.service.answer') }}</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="faqs[0][answer]" id="faqs[0][answer]"
                                        placeholder="{{ __('static.service.enter_answer') }}" cols="30" rows="5">{{ old('faqs[0][answer]') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-2 col-12">
                            <div class="form-group row">
                                <!-- <label
                                    class="col-12 opacity-0 d-sm-flex d-none">{{ __('static.service.action') }}</label> -->
                                <div class="col-12">
                                    <div class="remove-faq mt-4">
                                        <i data-feather="trash-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-11 col-10">
            <div class="form-group row mt-4">
                <label class="col-md-2"></label>
                <div class="col-md-10">
                    <button type="button" id="add-faq"
                        class="btn btn-secondary add-faq">{{ __('static.service.add_faq') }}</button>
                </div>
            </div>
        </div>
        <div class="footer">
            <button type="button" class="previousBtn btn cancel">{{ __('static.previous') }}</button>
            <button class="btn btn-primary submitBtn spinner-btn" type="submit">{{ __('static.submit') }}</button>
        </div>
    </div>
</div>

@push('js')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {

                const typeSelect = $('#type');
                const addressTab = $('#address_tab');
                const editAddressTab = $('#edit_address_tab');

                function toggleTabs() {
                    const isCreateRoute = window.location.pathname === '/backend/service/create';
                    if (typeSelect.val() === "{{ ServiceTypeEnum::PROVIDER_SITE }}" && !isCreateRoute) {

                        addressTab.addClass('d-none');
                        editAddressTab.removeClass('d-none');
                    } else {

                        editAddressTab.addClass('d-none');
                        addressTab.removeClass('d-none');
                    }
                }
                toggleTabs();
                typeSelect.on('change', toggleTabs);

                function isTabAddress() {
                    const isCreateRoute = window.location.pathname === '/backend/service/create';
                    const providerSiteType = "{{ ServiceTypeEnum::PROVIDER_SITE }}";

                    if (isCreateRoute || typeSelect.val() !== providerSiteType) {
                        return true;
                    }
                    return false;
                }

                function isTabDestinationAddress() {
                    const isCreateRoute = window.location.pathname !== '/backend/service/create';
                    const providerSiteType = "{{ ServiceTypeEnum::PROVIDER_SITE }}";
                    if (isCreateRoute && typeSelect.val() === providerSiteType) {

                        return true;
                    }
                    return false;
                }

                $(".autocomplete-google").autocomplete({
                    source: function(request, response) {
                        console.log("Autocomplete source called with request term:", request.term);

                        $.ajax({
                            url: '/backend/placeId',
                            type: 'GET',
                            dataType: "json",
                            data: {
                                inputData: request.term,
                            },
                            success: function(data) {
                                console.log("AJAX success:", data);
                                response(data);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log("AJAX error:", textStatus, errorThrown);
                            }
                        });
                    },
                    select: function(event, ui) {
                        console.log("Selected item:", ui.item);
                        var placeId = ui.item.id;
                        getAddressDetails(placeId);
                    }
                });

                function getAddressDetails(placeId) {
                    $.ajax({
                        url: "/backend/google-address",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            placeId: placeId,
                        },
                        success: function(data) {
                            console.log("address data", data.location)
                            $('#latitude').val(data.location.lat);
                            $('#longitude').val(data.location.lng);
                            $('#lat').val(data.location.lat);
                            $('#lng').val(data.location.lng);

                            $('#city').val(data.locality);
                            $('#postal_code').val(data.postal_code);
                            $('#postal_code').val(data.postal_code);
                            var street = '';
                            if (data.streetNumber) {
                                street += data.streetNumber + ", ";
                            }

                            if (data.streetName) {
                                street += data.streetName + ", ";
                            }
                            $('#street_address_1').val(street);
                            $('#area').val(data.area);
                            var countryId = data.country_id;
                            if (countryId) {
                                $('#country_id').val(countryId).trigger('change');
                            }

                            var stateId = data.state_id;
                            if (stateId) {
                                console.log("called");
                                $('.select-state').attr('data-default-state-id', stateId);
                                $('.select-state').val(stateId).trigger('change');
                                populateStates(countryId);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log("AJAX error in getAddressDetails:", textStatus,
                                errorThrown);
                        }
                    });
                }


                $("#serviceForm").validate({
                    ignore: [],
                    rules: {
                        "title": "required",
                        "category_id[]": "required",
                        "type": "required",
                        "user_id": {
                            required: isProvider
                        },
                        "required_servicemen": "required",
                        "price": "required",
                        "per_serviceman_commission": "required",
                        "tax_id": "required",
                        "duration": "required",
                        "duration_unit": "required",
                        "image[]": {
                            required: isServiceImage,
                            //accept: "image/jpeg, image/png"
                        },
                        "thumbnail": {
                            required: isServiceImage,
                            //accept: "image/jpeg, image/png"
                        },
                        "service_id[]": {
                            required: isServiceRelated
                        },
                        "country_id": {
                            required: isTabAddress
                        },
                        "state_id": {
                            required: isTabAddress
                        },
                        "postal_code": {
                            required: isTabAddress
                        },
                        "city": {
                            required: isTabAddress
                        },
                        "area": {
                            required: isTabAddress
                        },
                        "address": {
                            required: isTabAddress
                        },
                        "destination[country_id]": {
                            required: isTabDestinationAddress
                        },
                        "destination[state_id]": {
                            required: isTabDestinationAddress
                        },
                        "destination[postal_code]": {
                            required: isTabDestinationAddress
                        },
                        "destination[city]": {
                            required: isTabDestinationAddress
                        },
                        "destination[area]": {
                            required: isTabDestinationAddress
                        },
                        "destination[address]": {
                            required: isTabDestinationAddress
                        },
                    },
                    messages: {
                        "image[]": {
                            accept: "Only JPEG and PNG files are allowed.",
                        },
                    }
                });

                $('#add-faq').unbind().click(function() {
                    var allInputsFilled = true;

                    $('.faqs-structure').find('.form-group.row').each(function() {
                        var question = $(this).find('input[name^="faqs"]').val()?.trim();
                        var answer = $(this).find('input[name^="faqs"]').val()?.trim();

                        if (question === '' || answer === '') {
                            allInputsFilled = false;
                            $(this).find('input[name^="faqs"]').addClass('is-invalid');
                            $(this).find('input[name^="faqs"]').removeClass('is-valid');
                        } else {
                            $(this).find('input[name^="faqs"]').removeClass('is-invalid');
                        }

                    });


                    if (!allInputsFilled) {
                        return;
                    }

                    var inputGroup = $('.faqs-structure').last().clone();
                    var newIndex = $('.faqs-structure').length;

                    inputGroup.find('input[name^="faqs"]').each(function() {
                        var oldName = $(this).attr('name');
                        var newName = oldName.replace(/\[\d+\]/, '[' + newIndex + ']');
                        $(this).attr('name', newName).val('');
                    });

                    inputGroup.find('textarea[name^="faqs"]').each(function() {
                        var oldName = $(this).attr('name');
                        var newName = oldName.replace(/\[\d+\]/, '[' + newIndex + ']');
                        $(this).attr('name', newName).val('');
                    });

                    $(".faq-container").append(inputGroup);

                });

                $(document).on('click', '.remove-faq', function() {
                    $(this).closest('.faqs-structure').remove();
                });

                $('#price, #discount').on('input', updateServiceRate);
                updateServiceRate();

                $('select[name="type"]').on('change', function(e) {
                    var selectedType = $(this).val();
                });

                $(document).on('change', '#is_related', function(e) {
                    let status = $(this).prop('checked') == true ? 1 : 0;
                    if (status == true) {
                        $('.services').hide();
                    } else {
                        $('.services').show();
                    }
                });

                var initialProviderID = $('input[name="user_id"]').val() || $('select[name="user_id"]')
                    .val();

                if (initialProviderID) {
                    loadServices(initialProviderID);
                }
                @isset($service)
                    var selectedServices = {!! json_encode(
                        $service->related_services->where('id', '!=', $service->id)->pluck('id')->toArray() ?? [],
                    ) !!};
                    loadServices(initialProviderID, selectedServices);
                @endisset

                $('select[name="user_id"]').on('change', function() {
                    var providerID = $(this).val();
                    loadServices(providerID);
                });

                function loadServices(providerID, selectedServices) {

                    let url = "{{ route('backend.get-provider-services', '') }}";
                    if (providerID) {
                        $.ajax({
                            url: url,
                            type: "GET",
                            data: {
                                provider_id: providerID,
                                service_id: "{{ $service->id ?? null }}"
                            },
                            success: function(data) {
                                $('#related_services').empty();
                                $.each(data, function(id, optionData) {
                                    var imageUrl = optionData.media[0].original_url
                                    var option = new Option(optionData.title, optionData
                                        .id);

                                    $(option).attr("image", imageUrl);


                                    if (selectedServices && selectedServices.includes(
                                            String(optionData.id))) {
                                        $(option).prop("selected", true);
                                    }
                                    //
                                    $('#related_services').append(option);
                                });

                                $('#related_services').val(selectedServices).trigger('change');
                            },
                        });
                    }
                }
                var initialZoneID = $('select[name="zone_id[]"]').val();

                if (initialZoneID) {
                
                    loadCategories(initialZoneID);
                }

                @isset($service)
                    var selectedCategories = {!! json_encode($service->categories->pluck('id')->toArray() ?? []) !!};
                    loadCategories(initialZoneID, selectedCategories);
                @endisset

                $('select[name="zone_id[]"]').on('change', function() {
                    console.log("fdx");
                    var zoneId = $(this).val();
                    loadCategories(zoneId);
                });

                function loadCategories(zoneId, selectedCategories) {
                    console.log("selectedCategories",selectedCategories)
                    let url = "{{ route('backend.get-zone-categories', '') }}";
                    if (zoneId) {
                        $.ajax({
                            url: url,
                            type: "GET",
                            data: {
                                zone_id: zoneId,
                            },
                            success: function(data) {
                                $('#category_id').empty();
                                $.each(data, function(id, optionData) {

                                    var option = new Option(optionData, id);
                                     if (selectedCategories && selectedCategories.includes(id)) {
                                        $(option).prop("selected", true);
                                     }
                                    $('#category_id').append(option);
                                });

                                $('#category_id').val(selectedCategories).trigger('change');
                            },
                        });
                    }
                }


            });
        })(jQuery);

        function updateServiceRate() {
            var price = parseFloat($('#price').val()) || 0;
            var discount = parseFloat($('#discount').val()) || 0;
            var serviceRate = price - (price * (discount / 100));
            $('#service_rate').val(serviceRate.toFixed(2));
        }

        function isServiceImage() {
            @if (isset($service->media) && !$service->media->isEmpty())
                return false;
            @else
                return true;
            @endif
        }

        function isProvider() {
            @if (auth()->user()->hasrole('provider'))
                return false;
            @else
                return true;
            @endif
        }

        function isServiceRelated() {
            return $('#is_related').prop('checked') ? false : true;
        }
    </script>
@endpush
