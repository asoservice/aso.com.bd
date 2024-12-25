<div class="form-group row">
    <label class="col-md-2" for="name">{{ __('static.language.name') }}<span> *</span></label>
    <div class="col-md-10">
        <div class="input-group mb-3 phone-detail">
            <div class="col-sm-3">
                <select id="select-country" class="form-control form-select
                form-select-transparent" name="flag" data-placeholder="Select Flag">
                <option></option>
                @foreach (App\Helpers\Helpers::getCountryCodes() as $key => $option)
                    <option value="{{ $option->flag }}"
                        image="{{ asset('admin/images/flags/' . $option->flag) }}"
                        {{@$language?->flag == asset('admin/images/flags/' . $option->flag) ? 'selected' : '' }}>
                        {{ $option->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <input class="form-control" type="text" name="name" value="{{ isset($language->name ) ? $language->name  : old('name') }}" placeholder="{{ __('static.language.enter_language_name') }}">
            <!-- <div class="col-sm-9">
            </div> -->
            @error('name')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2" for="locale">{{ __('static.language.locale') }}<span> *</span></label>
    <div class="col-md-10 error-div select-dropdown">
        <select class="select-2 form-control" name="locale" data-placeholder="{{ __('static.language.select_locale') }}">
            <option></option>
            @foreach(config('enums.code_locales') as $key => $locale)
            @if($locale != @$language?->locale)
            <option class="option" @selected(old("locale", @$language->locale) == $key)
                value="{{$key}}">{{ $locale }}
            </option>
            @endif
            @endforeach
        </select>
        @error('locale')
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2" for="app_locale">{{ __('static.language.app_locale') }}<span> *</span></label>
    <div class="col-md-10 error-div select-dropdown">
        <select class="select-2 form-control" name="app_locale" data-placeholder="{{ __('static.language.select_app_locale') }}">
            <option></option>
            @foreach(config('enums.app_locales') as $key => $locale)
            @if($locale != @$language?->locale)
            <option class="option" @selected(old("locale", @$language->app_locale) == $key)
                value="{{$key}}">{{ $locale }}
            </option>
            @endif
            @endforeach
        </select>
        @error('app_locale')
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2" for="role">{{ __('static.language.is_rtl') }}</label>
    <div class="col-md-10">
        <div class="editor-space">
            <label class="switch">
                <input class="form-control" type="hidden" name="is_rtl" value="0">
                <input class="form-check-input" type="checkbox" name="is_rtl" id="" value="1" @checked(@$language?->is_rtl)>
                <span class="switch-state"></span>
            </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2" for="role">{{ __('static.status') }}</label>
    <div class="col-md-10">
        <div class="editor-space">
            <label class="switch">
                <input class="form-control" type="hidden" name="status" value="0">
                <input class="form-check-input" type="checkbox" name="status" id="" value="1" @checked(@$language?->status)>
                <span class="switch-state"></span>
            </label>
        </div>
    </div>
</div>

<div class="footer">
    <button type="button" class="btn cancel">{{ __('static.cancel') }}</button>
    <button id='submitBtn' type="submit" class="btn btn-primary spinner-btn">{{ __('static.submit') }}</button>
</div>


@push('js')
<script>
    (function($) {
        "use strict";
        $(document).ready(function() {
            $("#languageForm").validate({
                ignore: [],
                rules: {
                    "name": "required",
                    "locale": "required",
                    "app_locale": "required",
                }
            });
        });

        const optionFormat = (item) => {
            if (!item.id) {
                return item.text;
            }

            var span = document.createElement('span');
            var html = '';

            html += '<div class="selected-item">';
            html += '<img src="' + item.element.getAttribute('image') +
                '" class="h-30 w-30" alt="' + item.text + '"/>';3
                html += '<span>' + "  "+item.text + '</span>';
            html += '</div>';
            span.innerHTML = html;
            return $(span);
        }

        $('#select-country').select2({
            placeholder: "Select an option",
            templateSelection: optionFormat,
            templateResult: optionFormat
        });

    })(jQuery);
</script>
@endpush
