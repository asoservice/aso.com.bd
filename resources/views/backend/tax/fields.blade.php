<div class="form-group row">
    <label class="col-md-2" for="name">{{ __('static.name') }}<span> *</span></label>
    <div class="col-md-10">
        <input class="form-control" type="text" id="name" name="name" value="{{ isset($tax->name) ? $tax->name : old('name') }}"
            placeholder="{{ __('static.users.enter_name') }}">
        @error('name')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2" for="rate">{{ __('static.tax.rate') }}<span> *</span></label>
    <div class="col-md-10 error-div">
        <div class="input-group mb-3 flex-nowrap">
            <div class="w-100 percent">
                <input class="form-control" type="number" id="rate" name="rate" min="1" value="{{ isset($tax->rate) ? $tax->rate : old('price') }}" placeholder="{{ __('static.tax.enter_rate') }}"  oninput="if (value > 100) value = 100; if (value < 0) value = 0;">
            </div>
            <span class="input-group-text">%</span>
            @error('rate')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2" for="role">{{ __('static.status') }}</label>
    <div class="col-md-10">
        <div class="editor-space">
            <label class="switch">
                @if (isset($tax))
                    <input class="form-control" type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="" value="1"
                        {{ $tax->status ? 'checked' : '' }}>
                @else
                    <input class="form-control" type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="" value="1"
                        checked>
                @endif
                <span class="switch-state"></span>
            </label>
        </div>
    </div>
</div>

@push('js')
<script>
    (function($) {
    "use strict";
        $(document).ready(function() {
            $("#taxForm").validate({
                ignore: [],
                rules: {
                    "name": "required",  
                    "rate": "required",  
                }
            });
        });
    })(jQuery);
</script>
@endpush