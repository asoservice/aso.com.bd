@extends('backend.layouts.master')

@section('title', __('static.settings.settings'))

@section('content')
@use('App\Models\Settings')
@use('app\Helpers\Helpers')
@use('Nwidart\Modules\Facades\Module')
@php
$smsGateways = Helpers::getSMSGatewayList();
@endphp
<div class="card tab2-card">
    <div class="card-header">
        <h5>{{ __('static.settings.settings') }}</h5>
    </div>
    <div class="card-body">
        <div class="vertical-tabs">
            <div class="row g-xl-4 g-3">
                <div class="col-xxl-3 col-xl-4 col-12">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="general_settings_tab" data-bs-toggle="pill"
                            href="#general_settings">
                            <i class="ri-settings-line"></i>{{ __('static.settings.general') }}
                        </a>
                        <a class="nav-link" id="Ads_Setting_tab" data-bs-toggle="pill" href="#Ads_Setting">
                            <i class="ri-dice-1-line"></i>{{ __('static.settings.activation') }}
                        </a>
                        <a class="nav-link" id="Email_Setting_tab" data-bs-toggle="pill" href="#Email_Setting">
                            <i class="ri-mail-line"></i>{{ __('static.settings.email_configuration') }}
                        </a>
                        <a class="nav-link" id="App_Update_Popup_tab" data-bs-toggle="pill"
                            href="#App_Update_Popup">
                            <i class="ri-user-line"></i>{{ __('static.settings.provider_commissions') }}
                        </a>
                        <a class="nav-link" id="Service_Request_Setting_tab" data-bs-toggle="pill"
                            href="#Service_Request_Setting">
                            <i class="ri-arrow-right-circle-line"></i>{{ __('static.settings.service_request') }}
                        </a>
                        <a class="nav-link" id="Social_Login_tab" data-bs-toggle="pill" href="#Social_Login">
                            <i class="ri-global-line"></i>{{ __('static.settings.social_login') }}
                        </a>
                        <a class="nav-link" id="Google_Recaptcha_tab" data-bs-toggle="pill"
                            href="#Google_Recaptcha">
                            <i class="ri-chrome-line"></i>{{ __('static.settings.google_recaptcha') }}
                        </a>
                        <a class="nav-link" id="default_creation_limits_tab" data-bs-toggle="pill"
                            href="#default_creation_limits">
                            <i class="ri-error-warning-line"></i>{{ __('static.settings.default_creation_limits') }}
                        </a>
                        @if (Helpers::isModuleEnable('Subscription'))
                        @if (@$settings['activation']['subscription_enable'])
                        <a class="nav-link" id="subscription_plans_tab" data-bs-toggle="pill"
                            href="#subscription_plans" type="button">
                            <i class="ri-bank-card-line"></i>{{ __('static.settings.subscription') }}
                        </a>
                        @endif
                        @endif
                        <a class="nav-link" id="firebase_tab" data-bs-toggle="pill" href="#firebase">
                            <i class="ri-firebase-line"></i>{{ __('static.settings.firebase') }}
                        </a>
                        <a class="nav-link" id="agora_tab" data-bs-toggle="pill" href="#agora">
                            <i class="ri-video-on-line"></i>{{ __('static.settings.agora') }}
                        </a>
                    </div>
                </div>
                <div class="col-xxl-7 col-xl-8 col-12">
                    <form method="POST" class="needs-validation user-add" id="settingsForm"
                        action="{{ route('backend.update.settings', $settingsId) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="tab-content w-100" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="general_settings" role="tabpanel"
                                aria-labelledby="app_settings" tabindex="0">
                                <div>
                                    <div class="form-group row">
                                        <label for="image"
                                            class="col-md-2">{{ __('static.settings.light_logo') }}<span>*</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file" id="general[light_logo]"
                                                accept=".jpg, .png, .jpeg" name="general[light_logo]">
                                            @error('general[light_logo]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <span
                                                class="help-text">{{ __('static.settings.upload_logo_image_size') }}</span>
                                        </div>
                                    </div>
                                    @isset($settings['general']['light_logo'])
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">
                                                <div class="image-list">
                                                    <div class="image-list-detail">
                                                        <div class="position-relative">
                                                            <img src="{{ $settings['general']['light_logo'] }}"
                                                                id="{{ $settings['general']['light_logo'] }}"
                                                                alt="Light Logo" class="image-list-item">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endisset
                                    <div class="form-group row">
                                        <label for="image"
                                            class="col-md-2">{{ __('static.settings.dark_logo') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file" id="general[dark_logo]"
                                                accept=".jpg, .png, .jpeg" name="general[dark_logo]">
                                            @error('general[dark_logo]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <span
                                                class="help-text">{{ __('static.settings.upload_logo_image_size') }}</span>
                                        </div>
                                    </div>
                                    @isset($settings['general']['dark_logo'])
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">
                                                <div class="image-list">
                                                    <div class="image-list-detail">
                                                        <div class="position-relative">
                                                            <img src="{{ $settings['general']['dark_logo'] }}"
                                                                id="{{ $settings['general']['dark_logo'] }}"
                                                                alt="Dark Logo" class="image-list-item">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endisset
                                    <div class="form-group row">
                                        <label for="image"
                                            class="col-md-2">{{ __('static.settings.favicon') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file" id="general[favicon]"
                                                accept=".jpg, .png, .jpeg" name="general[favicon]">
                                            @error('general[favicon]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <span
                                                class="help-text">{{ __('static.settings.upload_favicon_image_size') }}</span>
                                        </div>
                                    </div>
                                    @isset($settings['general']['favicon'])
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-10">
                                                <div class="image-list">
                                                    <div class="image-list-detail">
                                                        <div class="position-relative">
                                                            <img src="{{ $settings['general']['favicon'] }}"
                                                                id="{{ $settings['general']['favicon'] }}"
                                                                alt="favicon" class="image-list-item">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endisset
                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="site_name">{{ __('static.settings.site_name') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="general[site_name]"
                                                name="general[site_name]"
                                                value="{{ $settings['general']['site_name'] ?? old('site_name') }}"
                                                placeholder="{{ __('static.settings.enter_site_name') }}">
                                            @error('general.site_name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="country" class="col-md-2">{{ __('static.timezone') }}<span>
                                                *</span></label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control select-country"
                                                id="general[default_timezone]" name="general[default_timezone]"
                                                data-placeholder="{{ __('static.settings.select_timezone') }}">
                                                <option class="select-placeholder" value=""></option>
                                                @forelse ($timeZones as $key => $option)
                                                <option class="option" value={{ $key }}
                                                    @if ($settings['general']['default_timezone'] ?? old('default_timezone')) @if ($key==$settings['general']['default_timezone']) selected @endif
                                                    @endif>{{ $option }}</option>
                                                @empty
                                                <option value="" disabled></option>
                                                @endforelse
                                            </select>
                                            @error('general[default_timezone]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="general[default_language_id]"
                                            class="col-md-2">{{ __('static.settings.default_language_id') }}<span>
                                                *</span></label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control select-country"
                                                id="general[default_language_id]" name="general[default_language_id]"
                                                data-placeholder="{{ __('static.settings.select_timezone') }}">
                                                <option class="select-placeholder" value=""></option>
                                                @forelse ($systemlangs as $key => $option)
                                                <option class="option" value={{ $key }}
                                                    @if ($settings['general']['default_language_id'] ?? old('default_language_id')) @if ($key==$settings['general']['default_language_id']) selected @endif
                                                    @endif>{{ $option }}</option>
                                                @empty
                                                <option value="" disabled></option>
                                                @endforelse
                                            </select>
                                            @error('general.default_language_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="country"
                                            class="col-md-2">{{ __('static.settings.currency') }}<span>
                                                *</span></label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control select-country"
                                                id="general[default_currency_id]" name="general[default_currency_id]"
                                                data-placeholder="{{ __('static.settings.select_currency') }}">
                                                <option class="select-placeholder" value=""></option>
                                                @forelse ($currencies as $key => $option)
                                                <option class="option" value={{ $key }}
                                                    @if ($settings['general']['default_currency_id'] ?? old('default_currency_id')) @if ($key==$settings['general']['default_currency_id']) selected @endif
                                                    @endif>{{ $option }}</option>
                                                @empty
                                                <option value="" disabled></option>
                                                @endforelse
                                            </select>
                                            @error('general[default_currency_id]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="country"
                                            class="col-md-2">{{ __('static.settings.sms_gateway') }}<span>*</span></label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control select-country"
                                                id="general[default_sms_gateway]" name="general[default_sms_gateway]"
                                                data-placeholder="{{ __('static.settings.select_sms_gateway') }}">
                                                <option class="select-placeholder" value=""></option>
                                                <option class="option" value="custom">
                                                    {{ __('Custom Sms Gateway') }}
                                                </option>
                                                @forelse ($smsGateways as $smsGateway)
                                                <option class="option" value="{{ $smsGateway['slug'] }}"
                                                    @if ($settings['general']['default_sms_gateway'] ?? old('default_sms_gateway')) @if ($smsGateway['slug']==$settings['general']['default_sms_gateway']) selected @endif
                                                    @endif>{{ $smsGateway['name'] }}</option>
                                                @empty
                                                <option value="" disabled></option>
                                                @endforelse
                                            </select>
                                            @error('general[default_sms_gateway]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="cancellation_restriction_hours">{{ __('static.settings.cancellation_restriction_hours') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text"
                                                id="general[cancellation_restriction_hours]"
                                                name="general[cancellation_restriction_hours]"
                                                value="{{ $settings['general']['cancellation_restriction_hours'] ?? old('cancellation_restriction_hours') }}"
                                                placeholder="{{ __('static.settings.enter_cancellation_restriction_hours') }}">
                                            @error('general[cancellation_restriction_hours]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <span
                                                class="help-text">{{ __('static.settings.cancellation_restriction_hours_help_text') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="general[min_booking_amount]">{{ __('static.settings.min_booking_amount') }}<span>
                                                *</span></label>
                                        <div class="col-md-10 error-div">
                                            <div class="input-group mb-3 flex-nowrap">
                                                <span
                                                    class="input-group-text">{{ Helpers::getSettings()['general']['default_currency']->symbol }}</span>
                                                <div class="w-100">
                                                    <input class='form-control' type="number"
                                                        id="general[min_booking_amount]"
                                                        name="general[min_booking_amount]" min="1"
                                                        value="{{ isset($settings['general']['min_booking_amount']) ? $settings['general']['min_booking_amount'] : old('general[min_booking_amount]') }}"
                                                        placeholder="{{ __('static.settings.enter_min_booking_amount') }}">
                                                    @error('general[min_booking_amount]')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                    <span
                                                        class="help-text">{{ __('static.settings.minimum_required_booking_amount') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="general[platform_fees]">{{ __('static.settings.platform_fees') }}<span>
                                                *</span></label>
                                        <div class="col-md-10 error-div">
                                            <div class="input-group mb-3 flex-nowrap">
                                                <span
                                                    class="input-group-text">{{ Helpers::getSettings()['general']['default_currency']->symbol }}</span>
                                                <div class="w-100">
                                                    <input class='form-control' type="number"
                                                        id="general[platform_fees]" name="general[platform_fees]"
                                                        min="1"
                                                        value="{{ isset($settings['general']['platform_fees']) ? $settings['general']['platform_fees'] : old('general[platform_fees]') }}"
                                                        placeholder="{{ __('static.settings.enter_platform_fees') }}">
                                                    @error('general[platform_fees]')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="general[platform_fees_type]"
                                            class="col-md-2">{{ __('static.settings.platform_fees_type') }}<span>
                                                *</span></label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control" id="general[platform_fees_type]"
                                                name="general[platform_fees_type]"
                                                data-placeholder="{{ __('static.settings.select_platform_fees_type') }}">
                                                <option class="select-placeholder" value=""></option>
                                                @forelse (['fixed' => 'Fixed', 'per_service' => 'Per Service'] as $key => $option)
                                                <option class="option" value={{ $key }}
                                                    @if ($settings['general']['platform_fees_type'] ?? old('platform_fees_type')) @if ($key==$settings['general']['platform_fees_type']) selected @endif
                                                    @endif>{{ $option }}</option>
                                                @empty
                                                <option value="" disabled></option>
                                                @endforelse
                                            </select>
                                            @error('general[platform_fees_type]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="country" class="col-md-2">{{ __('static.settings.mode') }}<span>
                                                *</span></label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control select-country" id="mode"
                                                name="general[mode]"
                                                data-placeholder="{{ __('static.settings.select_mode') }}">
                                                <option class="select-placeholder" value=""></option>
                                                @forelse (['dark' => 'Dark', 'light' => 'Light'] as $key => $option)
                                                <option class="option" value={{ $key }}
                                                    @if ($settings['general']['mode'] ?? old('mode')) @if ($key==$settings['general']['mode']) selected @endif
                                                    @endif>{{ $option }}</option>
                                                @empty
                                                <option value="" disabled></option>
                                                @endforelse
                                            </select>
                                            @error('mode')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="copyright_text">{{ __('static.settings.copyright_text') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" id="general[copyright]"
                                                name="general[copyright]"
                                                value="{{ $settings['general']['copyright'] ?? old('copyright') }}"
                                                placeholder="{{ __('static.settings.enter_copyright_text') }}">
                                            @error('general[copyright]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="Social_Login" role="tabpanel"
                                aria-labelledby="v-pills-settings-tab" tabindex="4">
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="social_login[client_id]">{{ __('static.settings.client_id') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="password" id="social_login[client_id]"
                                            name="social_login[client_id]"
                                            value="{{ Helpers::encryptKey($settings['social_login']['client_id']) ?? old('client_id') }}"
                                            placeholder="{{ __('static.settings.enter_client_id') }}">
                                        @error('social_login[client_id]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="social_login[client_secret]">{{ __('static.settings.client_secret') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="password" id="social_login[client_secret]"
                                            name="social_login[client_secret]"
                                            value="{{ Helpers::encryptKey($settings['social_login']['client_secret']) ?? old('client_secret') }}"
                                            placeholder="{{ __('static.settings.enter_client_secret') }}">
                                        @error('social_login[client_secret]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="social_login[redirect_url]">{{ __('static.settings.redirect_url') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="password" id="social_login[redirect_url]"
                                            name="social_login[redirect_url]"
                                            value="{{ Helpers::encryptKey($settings['social_login']['redirect_url']) ?? old('redirect_url') }}"
                                            placeholder="{{ __('static.settings.enter_redirect_url') }}">
                                        @error('social_login[redirect_url]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <span class="help-text">{{ __('static.settings.social_redirect_url') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="Ads_Setting" role="tabpanel"
                                aria-labelledby="v-pills-profile-tab" tabindex="1">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[service_auto_approve]">{{ __('static.settings.service_auto_approve') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['service_auto_approve']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[service_auto_approve]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[service_auto_approve]" value="1"
                                                        {{ $settings['activation']['service_auto_approve'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[service_auto_approve]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[service_auto_approve]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.service_auto_approve_span') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[additional_services]">{{ __('static.settings.additional_services') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['additional_services']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[additional_services]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[additional_services]" value="1"
                                                        {{ $settings['activation']['additional_services'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[additional_services]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[additional_services]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.additional_services_span') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[platform_fees_status]">{{ __('static.settings.platform_fees_status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['platform_fees_status']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[platform_fees_status]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[platform_fees_status]" value="1"
                                                        {{ $settings['activation']['platform_fees_status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[platform_fees_status]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[platform_fees_status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.platform_fees_status_span') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[blogs_enable]">{{ __('static.settings.blogs_enable') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['blogs_enable']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[blogs_enable]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[blogs_enable]" value="1"
                                                        {{ $settings['activation']['blogs_enable'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[blogs_enable]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[blogs_enable]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.blogs_enable_span') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[social_login_enable]">{{ __('static.settings.social_login_enable') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['social_login_enable']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[social_login_enable]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[social_login_enable]" value="1"
                                                        {{ $settings['activation']['social_login_enable'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[social_login_enable]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[social_login_enable]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.social_login_enable_span') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[maintenance_mode]">{{ __('static.settings.maintenance_mode') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['maintenance_mode']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[maintenance_mode]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[maintenance_mode]" value="1"
                                                        {{ $settings['activation']['maintenance_mode'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[maintenance_mode]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[maintenance_mode]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.maintenance_mode_span') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[subscription_enable]">{{ __('static.settings.subscription_enable') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['subscription_enable']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[subscription_enable]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[subscription_enable]" value="1"
                                                        {{ $settings['activation']['wallet_enable'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[subscription_enable]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[subscription_enable]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.subscription_span') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[wallet_enable]">{{ __('static.settings.wallet_enable') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['wallet_enable']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[wallet_enable]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[wallet_enable]" value="1"
                                                        {{ $settings['activation']['wallet_enable'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[wallet_enable]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[wallet_enable]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.wallet_span') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[coupon_enable]">{{ __('static.settings.coupon_enable') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['coupon_enable']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[coupon_enable]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[coupon_enable]" value="1"
                                                        {{ $settings['activation']['coupon_enable'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[coupon_enable]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[coupon_enable]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.coupon_span') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[cash]">{{ __('static.settings.cash') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['cash']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[cash]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[cash]" value="1"
                                                        {{ $settings['activation']['cash'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[cash=" 0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[cash]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span class="help-text">{{ __('static.settings.cash_span') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[provider_auto_approve]">{{ __('static.settings.auto_approve_provider') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['provider_auto_approve']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[provider_auto_approve]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[provider_auto_approve]" value="1"
                                                        {{ $settings['activation']['provider_auto_approve'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[provider_auto_approve]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[provider_auto_approve]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.provider_auto_approve') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[extra_charge_status]">{{ __('static.settings.extra_charge_status') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['extra_charge_status']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[extra_charge_status]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[extra_charge_status]" value="1"
                                                        {{ $settings['activation']['extra_charge_status'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[extra_charge_status]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[extra_charge_status]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.extra_charge_status_span') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[force_update_in_app]">{{ __('static.settings.force_update_in_app') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['force_update_in_app']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[force_update_in_app]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[force_update_in_app]" value="1"
                                                        {{ $settings['activation']['force_update_in_app'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[force_update_in_app]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[force_update_in_app]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.force_update_in_app_span') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="activation[default_credentials]">{{ __('static.settings.default_credentials') }}</label>
                                        <div class="col-md-10">
                                            <div class="editor-space">
                                                <label class="switch">
                                                    @if (isset($settings['activation']['default_credentials']))
                                                    <input class="form-control" type="hidden"
                                                        name="activation[default_credentials]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[default_credentials]" value="1"
                                                        {{ $settings['activation']['default_credentials'] ? 'checked' : '' }}>
                                                    @else
                                                    <input class="form-control" type="hidden"
                                                        name="activation[default_credentials]" value="0">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="activation[default_credentials]" value="1">
                                                    @endif
                                                    <span class="switch-state"></span>
                                                </label>
                                                <span
                                                    class="help-text">{{ __('static.settings.default_credentials_span') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="Email_Setting" role="tabpanel"
                                aria-labelledby="v-pills-settings-tab" tabindex="2">

                                <div>
                                    <div class="form-group row">
                                        <label for="country"
                                            class="col-md-2">{{ __('static.settings.mailer') }}<span>
                                                *</span></label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control select-country"
                                                id="email[mail_mailer]" name="email[mail_mailer]"
                                                data-placeholder="{{ __('static.settings.select_mail_mailer') }}">
                                                <option class="select-placeholder" value=""></option>
                                                @forelse (['smtp' => 'SMTP', 'sendmail' => 'Sendmail'] as $key => $option)
                                                <option class="option" value={{ $key }}
                                                    @if ($settings['email']['mail_mailer'] ?? old('mail_mailer')) @if ($key==$settings['email']['mail_mailer']) selected @endif
                                                    @endif>{{ $option }}</option>
                                                @empty
                                                <option value="" disabled></option>
                                                @endforelse
                                            </select>
                                            @error('mode')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="mail_host">{{ __('static.settings.host') }}<span> *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" name="email[mail_host]"
                                                id="email[mail_host]"
                                                value="{{ isset($settings['email']['mail_host']) ? $settings['email']['mail_host'] : old('mail_host') }}"
                                                placeholder="{{ __('static.settings.enter_host') }}">
                                            @error('email[mail_host]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="mail_port">{{ __('static.settings.port') }}<span> *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="number" min="1"
                                                name="email[mail_port]" id="email[mail_port]"
                                                value="{{ isset($settings['email']['mail_port']) ? $settings['email']['mail_port'] : old('mail_host') }}"
                                                placeholder="{{ __('static.settings.enter_port') }}">
                                            @error('mail_port')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="country"
                                            class="col-md-2">{{ __('static.settings.mail_encryption') }}<span>
                                                *</span></label>
                                        <div class="col-md-10 error-div select-dropdown">
                                            <select class="select-2 form-control select-country"
                                                id="email[mail_encryption]" name="email[mail_encryption]"
                                                data-placeholder="{{ __('static.settings.select_mail_encryption') }}">
                                                <option class="select-placeholder" value=""></option>
                                                @forelse (['tls' => 'TLS', 'ssl' => 'SSL'] as $key => $option)
                                                <option class="option" value={{ $key }}
                                                    @if ($settings['email']['mail_encryption'] ?? old('mail_encryption')) @if ($key==$settings['email']['mail_encryption']) selected @endif
                                                    @endif>{{ $option }}</option>
                                                @empty
                                                <option value="" disabled></option>
                                                @endforelse
                                            </select>
                                            @error('mode')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="mail_username">{{ __('static.settings.mail_username') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" name="email[mail_username]"
                                                id="email[mail_username]"
                                                value="{{ isset($settings['email']['mail_username']) ? $settings['email']['mail_username'] : old('mail_username') }}"
                                                placeholder="{{ __('static.settings.enter_username') }}">
                                            @error('mail_username')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="password">{{ __('static.settings.mail_password') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="password" name="email[mail_password]"
                                                id="email[mail_password]"
                                                value="{{ isset($settings['email']['mail_password']) ? Helpers::encryptKey($settings['email']['mail_password']) : old('mail_password') }}"
                                                placeholder="{{ __('static.settings.enter_password') }}">
                                            @error('mail_password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="mail_from_name">{{ __('static.settings.mail_from_name') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" name="email[mail_from_name]"
                                                id="email[mail_from_name]"
                                                value="{{ isset($settings['email']['mail_from_name']) ? $settings['email']['mail_from_name'] : old('mail_from_name') }}"
                                                placeholder="{{ __('static.settings.enter_email_from_name') }}">
                                            @error('mail_from_name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="mail_from_address">{{ __('static.settings.mail_from_address') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text"
                                                name="email[mail_from_address]" id="email[mail_from_address]"
                                                value="{{ isset($settings['email']['mail_from_address']) ? $settings['email']['mail_from_address'] : old('mail_from_address') }}"
                                                placeholder="{{ __('static.settings.enter_email_from_address') }}">
                                            @error('mail_from_address')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h4 class="fw-semibold mb-3 theme-color w-100">{{ __('static.test_mail') }}</h4>
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="mail">{{ __('static.settings.to_mail') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="mail" id="mail"
                                            placeholder="{{ __('static.enter_email') }}">
                                        @error('mail')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <button id="send-test-mail" name="test_mail" class="btn btn-primary">
                                    <span class="btn-text">{{ __('static.settings.send_test_mail') }}</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                                <div class="instruction-box">
                                    <div class="instruction-title">
                                        <h4>{{ __('static.instruction') }}</h4>
                                        <p class="text-danger">
                                            {{ __('static.settings.test_mail_note') }}
                                        </p>
                                    </div>
                                    <div class="list-box">
                                        <h5>{{ __('static.settings.test_mail_not_using_ssl') }}</h5>
                                        <ul>
                                            <li>{{ __('static.settings.test_mail_not_ssl_msg_1') }}</li>
                                            <li>{{ __('static.settings.test_mail_not_ssl_msg_2') }}</li>
                                            <li>{{ __('static.settings.test_mail_not_ssl_msg_3') }}</li>
                                            <li>{{ __('static.settings.test_mail_not_ssl_msg_4') }}</li>
                                        </ul>
                                    </div>
                                    <div class="list-box">

                                        <h5>{{ __('static.settings.test_mail_using_ssl') }}</h5>
                                        <ul>
                                            <li>{{ __('static.settings.test_mail_ssl_msg_1') }}</li>
                                            <li>{{ __('static.settings.test_mail_ssl_msg_2') }}</li>
                                            <li>{{ __('static.settings.test_mail_ssl_msg_3') }}</li>
                                            <li>{{ __('static.settings.test_mail_ssl_msg_4') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="App_Update_Popup" role="tabpanel"
                                aria-labelledby="v-pills-settings-tab" tabindex="3">
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="provider_commissions[status]">{{ __('static.settings.status') }}</label>
                                    <div class="col-md-10">
                                        <div class="editor-space">
                                            <label class="switch">
                                                @if (isset($settings['provider_commissions']['status']))
                                                <input class="form-control" type="hidden"
                                                    name="provider_commissions[status]" value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="provider_commissions[status]" value="1"
                                                    {{ $settings['provider_commissions']['status'] ? 'checked' : '' }}>
                                                @else
                                                <input class="form-control" type="hidden"
                                                    name="provider_commissions[status]" value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="provider_commissions[status]" value="1">
                                                @endif
                                                <span class="switch-state"></span>
                                            </label>
                                            <span
                                                class="help-text">{{ __('static.settings.activation_vendor_commissions') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="provider_commissions[min_withdraw_amount]">{{ __('static.settings.min_withdraw_amount') }}</label>
                                    <div class="col-md-10 error-div">
                                        <div class="input-group mb-3 flex-nowrap">
                                            <span
                                                class="input-group-text">{{ Helpers::getSettings()['general']['default_currency']->symbol }}</span>
                                            <div class="w-100">
                                                <input class='form-control' type="number"
                                                    id="provider_commissions[min_withdraw_amount]"
                                                    name="provider_commissions[min_withdraw_amount]" min="1"
                                                    value="{{ isset($settings['provider_commissions']['min_withdraw_amount']) ? $settings['provider_commissions']['min_withdraw_amount'] : old('min_withdraw_amount') }}"
                                                    placeholder="{{ __('static.settings.enter_min_withdraw_amount') }}">
                                                @error('provider_commissions[min_withdraw_amount]')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                <span
                                                    class="help-text">{{ __('static.settings.min_amount_for_vendor_withdraw') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="provider_commissions[default_commission_rate]">{{ __('static.settings.commission_rate') }}</label>
                                    <div class="col-md-10 error-div">
                                        <div class="input-group mb-3 flex-nowrap">
                                            <div class="w-100 percent">
                                                <input class='form-control'
                                                    id="provider_commissions[default_commission_rate]" type="number"
                                                    name="provider_commissions[default_commission_rate]"
                                                    min="1"
                                                    value="{{ $settings['provider_commissions']['default_commission_rate'] ?? old('discount') }}"
                                                    placeholder="{{ __('static.settings.enter_default_commission_rate') }}"
                                                    oninput="if (value > 100) value = 100; if (value < 0) value = 0;">
                                                <span
                                                    class="help-text">{{ __('static.settings.set_rate_admin_receive_commission') }}</span>
                                            </div>
                                            <span class="input-group-text">%</span>
                                            @error('provider_commissions[default_commission_rate]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="provider_commissions[is_category_based_commission]">{{ __('static.settings.is_category_based_commission') }}</label>
                                    <div class="col-md-10">
                                        <div class="editor-space">
                                            <label class="switch">
                                                @if (isset($settings['provider_commissions']['is_category_based_commission']))
                                                <input class="form-control" type="hidden"
                                                    name="provider_commissions[is_category_based_commission]"
                                                    value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="provider_commissions[is_category_based_commission]"
                                                    value="1"
                                                    {{ $settings['provider_commissions']['is_category_based_commission'] ? 'checked' : '' }}>
                                                @else
                                                <input class="form-control" type="hidden"
                                                    name="provider_commissions[is_category_based_commission]"
                                                    value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="provider_commissions[is_category_based_commission]"
                                                    value="1">
                                                @endif
                                                <span class="switch-state"></span>
                                            </label>
                                            <span
                                                class="help-text">{{ __('static.settings.enable_service_category_based_commissions') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="Service_Request_Setting" role="tabpanel"
                                aria-labelledby="v-pills-settings-tab" tabindex="4">
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="service_request[per_serviceman_commission]">{{ __('static.settings.per_serviceman_commission') }}<span>
                                            *</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text"
                                            id="service_request[per_serviceman_commission]"
                                            name="service_request[per_serviceman_commission]"
                                            value="{{ $settings['service_request']['per_serviceman_commission'] ?? old('per_serviceman_commission') }}"
                                            placeholder="{{ __('static.settings.enter_per_serviceman_commission') }}">
                                        @error('service_request[per_serviceman_commission]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="service_request[default_tax_id]"
                                        class="col-md-2">{{ __('static.settings.default_tax_id') }}<span>
                                            *</span></label>
                                    <div class="col-md-10 error-div select-dropdown">
                                        <select class="select-2 form-control select-country"
                                            id="service_request[default_tax_id]"
                                            name="service_request[default_tax_id]"
                                            data-placeholder="{{ __('static.settings.select_default_tax_id') }}">
                                            <option class="select-placeholder" value=""></option>
                                            @forelse ($taxes as $key => $option)
                                            <option class="option" value={{ $key }}
                                                @if ($settings['service_request']['default_tax_id'] ?? old('default_tax_id')) @if ($key==$settings['service_request']['default_tax_id']) selected @endif
                                                @endif>{{ $option }}</option>
                                            @empty
                                            <option value="" disabled></option>
                                            @endforelse
                                        </select>
                                        @error('service_request[default_tax_id]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="service_request[status]">{{ __('static.settings.status') }}</label>
                                    <div class="col-md-10">
                                        <div class="editor-space">
                                            <label class="switch">
                                                @if (isset($settings['service_request']['status']))
                                                <input class="form-control" type="hidden"
                                                    name="service_request[status]" value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="service_request[status]" value="1"
                                                    {{ $settings['service_request']['status'] ? 'checked' : '' }}>
                                                @else
                                                <input class="form-control" type="hidden"
                                                    name="service_request[status]" value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="service_request[status]" value="1">
                                                @endif
                                                <span class="switch-state"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="Google_Recaptcha" role="tabpanel"
                                aria-labelledby="v-pills-settings-tab" tabindex="4">
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="google_reCaptcha[secret]">{{ __('static.settings.secret') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="password" id="google_reCaptcha[secret]"
                                            name="google_reCaptcha[secret]"
                                            value="{{ Helpers::encryptKey($settings['google_reCaptcha']['secret']) ?? old('secret') }}"
                                            placeholder="{{ __('static.settings.enter_secret') }}">
                                        @error('google_reCaptcha[secret]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="google_reCaptcha[site_key]">{{ __('static.settings.site_key') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="password" id="google_reCaptcha[site_key]"
                                            name="google_reCaptcha[site_key]"
                                            value="{{ Helpers::encryptKey($settings['google_reCaptcha']['site_key']) ?? old('site_key') }}"
                                            placeholder="{{ __('static.settings.enter_site_key') }}">
                                        @error('google_reCaptcha[site_key]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="google_reCaptcha[status]">{{ __('static.settings.status') }}</label>
                                    <div class="col-md-10">
                                        <div class="editor-space">
                                            <label class="switch">
                                                @if (isset($settings['google_reCaptcha']['status']))
                                                <input class="form-control" type="hidden"
                                                    name="google_reCaptcha[status]" value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="google_reCaptcha[status]" value="1"
                                                    {{ $settings['google_reCaptcha']['status'] ? 'checked' : '' }}>
                                                @else
                                                <input class="form-control" type="hidden"
                                                    name="google_reCaptcha[status]" value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="google_reCaptcha[status]" value="1">
                                                @endif
                                                <span class="switch-state"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="default_creation_limits" role="tabpanel"
                                aria-labelledby="v-pills-settings-tab" tabindex="6">
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="default_creation_limits[allowed_max_services]">{{ __('static.settings.services') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text"
                                            id="default_creation_limits[allowed_max_services]"
                                            name="default_creation_limits[allowed_max_services]"
                                            value="{{ $settings['default_creation_limits']['allowed_max_services'] ?? old('allowed_max_services') }}"
                                            placeholder="{{ __('static.settings.enter_min_services_limit') }}">
                                        @error('default_creation_limits[allowed_max_services]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <span
                                            class="help-text">{{ __('static.settings.max_service_provider_can_create') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="default_creation_limits[allowed_max_addresses]">{{ __('static.settings.addresses') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text"
                                            id="default_creation_limits[allowed_max_addresses]"
                                            name="default_creation_limits[allowed_max_addresses]"
                                            value="{{ $settings['default_creation_limits']['allowed_max_addresses'] ?? old('allowed_max_addresses') }}"
                                            placeholder="{{ __('static.settings.enter_min_services_limit') }}">
                                        @error('default_creation_limits[allowed_max_addresses]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <span
                                            class="help-text">{{ __('static.settings.max_addresses_provider_can_create') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="default_creation_limits[allowed_max_servicemen]">{{ __('static.settings.servicemen') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text"
                                            id="default_creation_limits[allowed_max_servicemen]"
                                            name="default_creation_limits[allowed_max_servicemen]"
                                            value="{{ $settings['default_creation_limits']['allowed_max_servicemen'] ?? old('allowed_max_servicemen') }}"
                                            placeholder="{{ __('static.settings.enter_min_servicemen_limit') }}">
                                        @error('default_creation_limits[allowed_max_servicemen]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <span
                                            class="help-text">{{ __('static.settings.max_servicemen_provider_can_create') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="default_creation_limits[allowed_max_service_packages]">{{ __('static.settings.service_packages') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text"
                                            id="default_creation_limits[allowed_max_service_packages]"
                                            name="default_creation_limits[allowed_max_service_packages]"
                                            value="{{ $settings['default_creation_limits']['allowed_max_service_packages'] ?? old('allowed_max_service_packages') }}"
                                            placeholder="{{ __('static.settings.enter_min_service_packages_limit') }}">
                                        @error('default_creation_limits[allowed_max_service_packages]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <span
                                            class="help-text">{{ __('static.settings.max_service_packages_provider_can_create') }}</span>
                                    </div>
                                </div>
                            </div>
                            @if (@$settings['activation']['subscription_enable'])
                            <div class="tab-pane fade" id="subscription_plans" role="tabpanel"
                                aria-labelledby="v-pills-settings-tab" tabindex="7">
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="subscription_plan['free_trial_days]">{{ __('static.settings.free_trial_days') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" min="1"
                                            id="subscription_plan[free_trial_days]"
                                            name="subscription_plan[free_trial_days]"
                                            value="{{ $settings['subscription_plan']['free_trial_days'] ?? old('free_trial_days') }}"
                                            placeholder="{{ __('static.settings.enter_free_trial_days') }}">
                                        @error('subscription_plan[free_trial_days]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <span
                                            class="help-text">{{ __('static.settings.free_trail_days') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="subscription_plan[days_before_reminder]">{{ __('static.settings.days_before_reminder') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" min="1"
                                            id="subscription_plan[days_before_reminder]"
                                            name="subscription_plan[days_before_reminder]"
                                            value="{{ $settings['subscription_plan']['days_before_reminder'] ?? old('days_before_reminder') }}"
                                            placeholder="{{ __('static.settings.enter_days_before_reminder') }}">
                                        @error('subscription_plan[days_before_reminder]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <span
                                            class="help-text">{{ __('static.settings.days_before_reminder_span') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="subscription_plan[reminder_message]">{{ __('static.settings.reminder_message') }}</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" min="1"
                                            id="subscription_plan[reminder_message]"
                                            name="subscription_plan[reminder_message]"
                                            value="{{ $settings['subscription_plan']['reminder_message'] ?? old('reminder_message') }}"
                                            placeholder="{{ __('static.settings.enter_reminder_message') }}">
                                        @error('subscription_plan[reminder_message]')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <span
                                            class="help-text">{{ __('static.settings.reminder_message_span') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2"
                                        for="subscription_plan[free_trial_enabled]">{{ __('static.settings.status') }}</label>
                                    <div class="col-md-10">
                                        <div class="editor-space">
                                            <label class="switch">
                                                @if (isset($settings['subscription_plan']['free_trial_enabled']))
                                                <input class="form-control" type="hidden"
                                                    name="subscription_plan[free_trial_enabled]"
                                                    value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="subscription_plan[free_trial_enabled]"
                                                    value="1"
                                                    {{ $settings['subscription_plan']['free_trial_enabled'] ? 'checked' : '' }}>
                                                @else
                                                <input class="form-control" type="hidden"
                                                    name="subscription_plan[free_trial_enabled]"
                                                    value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    name="subscription_plan[free_trial_enabled]"
                                                    value="1">
                                                @endif
                                                <span class="switch-state"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="tab-pane fade" id="firebase" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="8">
                                <div>
                                    <div class="form-group row">
                                        <label for="image"
                                            class="col-md-2">{{ __('static.settings.firebase_service_json') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="file" id="firebase[service_json]"
                                                accept="application/JSON" name="firebase[service_json]">
                                            @error('firebase[service_json]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="google_map_api_key">{{ __('static.settings.google_map_api_key') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="password"
                                                id="firebase[google_map_api_key]"
                                                name="firebase[google_map_api_key]"
                                                value="{{ Helpers::encryptKey($settings['firebase']['google_map_api_key']) ?? old('google_map_api_key') }}"
                                                placeholder="{{ __('static.settings.enter_google_map_api_key') }}">
                                            @error('firebase[google_map_api_key]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="agora" role="tabpanel"
                                aria-labelledby="v-pills-settings-tab" tabindex="9">
                                <div>
                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="agora[app_id]">{{ __('static.settings.agora_app_id') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="password" id="agora[app_id]"
                                                name="agora[app_id]"
                                                value="{{ Helpers::encryptKey($settings['agora']['app_id']) ?? old('agora[app_id]') }}"
                                                placeholder="{{ __('static.settings.enter_agora_app_id') }}">
                                            @error('agora[app_id]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2"
                                            for="agora[certificate]">{{ __('static.settings.agora_certificate') }}<span>
                                                *</span></label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="password" id="agora[certificate]"
                                                name="agora[certificate]"
                                                value="{{ Helpers::encryptKey($settings['agora']['certificate']) ?? old('agora[certificate]') }}"
                                                placeholder="{{ __('static.settings.enter_agora_certificate') }}">
                                            @error('agora[certificate]')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit"
                                class="btn btn-primary spinner-btn nextBtn">{{ __('static.save') }}</button>
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

        $('#send-test-mail').click(function(e) {
            e.preventDefault();

            var form = $('#settingsForm');
            var url = form.attr('action');
            var formData = form.serializeArray();
            var additionalData = {
                test_mail: 'true',
            };

            $.each(additionalData, function(key, value) {
                formData.push({
                    name: key,
                    value: value
                });
            });

            $('#send-test-mail').prop('disabled', true);
            $('#send-test-mail .btn-text').text('Sending...'); 
            $('#send-test-mail .spinner-border').removeClass('d-none');

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                success: function(response) {

                    let obj = JSON.parse(response);
                    console.log(obj);
                    if (obj.success == true) {
                        toastr.success(obj.message);
                    } else {
                        toastr.error(obj.message);
                    }
                },
                error: function(response) {
                    obj = JSON.parse(response);
                    console.log(obj);
                    toastr.error(obj.message, 'Error');
                },
                complete: function() {
                    $('#send-test-mail').prop('disabled', false);
                    $('#send-test-mail .btn-text').text('{{ __('static.settings.send_test_mail') }}');
                    $('#send-test-mail .spinner-border').addClass('d-none'); 
                }
            });
        });

        $("#settingsForm").validate({
            ignore: [],
            rules: {
                "general[light_logo]": {
                    required: isLightLogo,
                    accept: "image/jpeg, image/png"
                },
                "general[dark_logo]": {
                    required: isDarkLogo,
                    accept: "image/jpeg, image/png"
                },
                "general[favicon]": {
                    required: isFavicon,
                    accept: "image/jpeg, image/png"
                },
                "firebase[service_json]": {
                    required: isFirebaseServiceJson,
                    accept: "application/JSON"
                },
                "email[mail_mailer]": "required",
                "email[mail_host]": "required",
                "email[mail_port]": "required",
                "email[mail_encryption]": "required",
                "email[mail_username]": "required",
                "email[mail_password]": "required",
                "email[mail_from_name]": "required",
                "email[mail_from_address]": "required",
                "general[site_name]": "required",
                "general[default_language_id]": "required",
                "general[default_currency_id]": "required",
                "general[default_sms_gateway]": "required",
                "general[min_booking_amount]": "required",
                "general[platform_fees]": "required",
                "general[platform_fees_type]": "required",
                "general[mode]": "required",
                "general[copyright]": "required",
                "service_request[per_serviceman_commission]": "required",
                "service_request[default_tax_id]": "required",
                "firebase[google_map_api_key]": "required",
                "agora[app_id]": "required",
                "agora[certificate]": "required",
            },
            invalidHandler: function(event, validator) {
                let invalidTabs = [];

                $.each(validator.errorList, function(index, error) {
                    const tabId = $(error.element).closest('.tab-pane').attr('id');
                    console.log(tabId);
                    $("#" + tabId + "-tab .errorIcon").show();
                    if (!invalidTabs.includes(tabId)) {
                        invalidTabs.push(tabId);
                    }
                });
                if (invalidTabs.length) {
                    $(".nav-link.active").removeClass("active");
                    $(".tab-pane.show").removeClass("show active");
                    $("#" + invalidTabs[0] + "_tab").addClass("active");
                    $("#" + invalidTabs[0]).addClass("show active");
                }
            },
            success: function(label, element) {

            }
        });

        function isFavicon() {
            @if(isset($settings['general']['favicon']))
            return false;
            @else
            return true;
            @endif
        }

        function isLightLogo() {
            @if(isset($settings['general']['light_logo']))
            return false;
            @else
            return true;
            @endif
        }

        function isDarkLogo() {
            @if(isset($settings['general']['dark_logo']))
            return false;
            @else
            return true;
            @endif
        }

        function isFirebaseServiceJson() {
            @if(isset($settings['firebase']['service_json']))
            return false;
            @else
            return true;
            @endif
        }
    });
</script>
@endpush
