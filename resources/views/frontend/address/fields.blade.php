@use('app\Helpers\Helpers')
@use('app\Models\State')
@php
    $countries = Helpers::getCountries();
    $countryCodes = Helpers::getCountryCodes();
    $states = [];
    if (isset($address->country_id) || old('country_id')) {
        $states = State::where('country_id', old('country_id', @$address->country_id))?->get();
    }
@endphp

<div class="row g-3">
    <div class="col-12">
        <div class="category-list-box">
            <label class="label-title" for="role">{{ __('static.address_category') }}</label>
            <div class="form-group category-list">
                <div class="form-check form-radio">
                    <label class="form-check-label mb-0 cursor-pointer" for="home">{{ __('static.home') }}</label>
                    <input type="radio" name="address_type" id="home" value="Home" class="form-check-input"
                        @isset($address->type){{ $address->type == 'Home' ? 'checked' : '' }}@endisset
                        checked>
                </div>
                <div class="form-check form-radio">
                    <label class="form-check-label mb-0 cursor-pointer" for="work">{{ __('static.work') }}</label>
                    <input type="radio" name="address_type" id="work" value="Work" class="form-check-input"
                        @isset($address->type){{ $address->type == 'Work' ? 'checked' : '' }}@endisset>
                </div>
                <div class="form-check form-radio">
                    <label class="form-check-label mb-0 cursor-pointer" for="other">{{ __('static.other') }}</label>
                    <input type="radio" name="address_type" id="other" value="Other" class="form-check-input"
                        @isset($address->type){{ $address->type == 'Other' ? 'checked' : '' }}@endisset>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="category-list-box">
            <label class="label-title" for="alternative_name">{{ __('static.address.alternative_name') }}</label>
            <div class="w-100">
                <input class='form-control' type="text" name="alternative_name" id="alternative_name"
                    value="{{ $address->alternative_name ?? old('alternative_name') }}"
                    placeholder="{{ __('static.address.enter_alternative_name') }}">
                @error('alternative_name')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="category-list-box">
            <label class="label-title" for="alternative_phone">{{ __('static.address.alternative_phone') }}</label>
            <div class="w-100">
                <div class="input-group phone-detail">
                    <select class="select-2 form-control select-country-code" name="code" data-placeholder="">
                        @php
                            $default = old('alternative_code', $address->code ?? 1);
                        @endphp
                        <option value="" selected></option>
                        @foreach (Helpers::getCountryCodes() as $key => $option)
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
                    <input class="form-control" type="number" name="alternative_phone" id="alternative_phone"
                        value="{{ $address->alternative_phone ?? old('alternative_phone') }}" min="1"
                        placeholder="{{ __('static.address.enter_alternative_phone') }}">
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="category-list-box">
            <label class="label-title" for="address">{{ __('static.users.address') }} <span
                    class="required-span">*</span></label>
            <div class="w-100">
                <textarea class="form-control ui-widget autocomplete-google" placeholder="Enter Address " rows="4" id="address"
                    name="address" cols="50">{{ $address->address ?? old('address') }}</textarea>
                @error('address')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="category-list-box">
            <label class="label-title" for="country">{{ __('static.users.country') }} <span
                    class="required-span">*</span></label>
            <div class="w-100 error-div select-dropdown border-0 p-0 m-0">
                <select class="select-2 form-control select-country" id="country_id" name="country_id"
                    data-placeholder="{{ __('static.users.select_country') }}" required>
                    <option class="select-placeholder" value=""></option>
                    @php
                        $default = old('country_id', @$address->country_id);
                    @endphp
                    @foreach ($countries as $key => $option)
                        <option class="option" value={{ $key }}
                            @if ($key == $default) selected @endif data-default="{{ $default }}">
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
                @error('country_id')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="category-list-box">
            <label class="label-title" for="state">{{ __('static.users.state') }} <span
                    class="required-span">*</span></label>
            <div class="w-100 error-div select-dropdown border-0 p-0 m-0">
                <select class="select-2 form-control select-state"
                    data-placeholder="{{ __('static.users.select_state') }}" id="state_id" name="state_id"
                    data-default-state-id="{{ $address->state_id ?? '' }}" required>
                    <option class="select-placeholder" value=""></option>
                    @php
                        $default = old('state_id', @$address->state_id);
                    @endphp
                    @if (count($states))
                        @foreach ($states as $key => $state)
                            <option class="option" value={{ $state->id }}
                                @if ($state->id == $default) selected @endif data-default="{{ $default }}">
                                {{ $state->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @error('state_id')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="category-list-box">
            <label class="label-title" for="branch_name">{{ __('static.city') }} <span
                    class="required-span">*</span></label>
            <div class="w-100">
                <input class="form-control" id="city" type="text" name="city"
                    value="{{ isset($address->city) ? $address->city : old('city') }}"
                    placeholder="{{ __('static.users.enter_city') }}" required>
                @error('city')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="category-list-box">
            <label class="label-title" for="area">{{ __('static.area') }} <span
                    class="required-span">*</span></label>
            <div class="w-100">
                <input class="form-control" type="text" id="area" name="area"
                    value="{{ isset($address->area) ? $address->area : old('area') }}"
                    placeholder="{{ __('static.users.enter_area') }}" required>
                @error('area')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="category-list-box">
            <label class="label-title" for="postal_code">{{ __('static.postal_code') }} <span
                    class="required-span">*</span></label>
            <div class="w-100">
                <input class="form-control" type="text" id="postal_code" name="postal_code"
                    value="{{ isset($address->postal_code) ? $address->postal_code : old('postal_code') }}"
                    placeholder="{{ __('static.users.postal_code') }}" required>
                @error('postal_code')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $(".select-2").select2();

                $('.select-country').on('change', function() {
                    var idCountry = $(this).val();
                    populateStates(idCountry);
                });

                function populateStates(countryId) {
                    $(".select-state").html('');
                    $.ajax({
                        url: "{{ url('/states') }}",
                        type: "POST",
                        data: {
                            country_id: countryId,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            $('.select-state').html('<option value="">Select State</option>');
                            $.each(result.states, function(key, value) {
                                $(".select-state").append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                            var defaultStateId = $(".select-state").data("default-state-id");
                            if (defaultStateId !== '') {
                                $('.select-state').val(defaultStateId);
                            }
                        }
                    });
                }

                $("#addressForm").validate({
                    ignore: [],
                    rules: {
                        "country_id": "required",
                        "state_id": "required",
                        "city": "required",
                        "area": "required",
                        "postal_code": "required",
                        "address": "required"
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
