<div class="form-group row">
    <label class="col-md-2" for="name">{{ __('static.name') }}<span> *</span></label>
    <div class="col-md-10">
        <input class="form-control" type="text" id="name" name="name"
            placeholder="{{ __('static.tag.enter_name') }}" value="{{ isset($tag->name) ? $tag->name : old('name') }}">
        @error('name')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class = "form-group row">
    <label for="address" class="col-md-2">{{ __('static.tag.description') }}<span> *</span></label>
    <div class="col-md-10">
        <textarea class = "form-control" id="description" placeholder="{{ __('static.tag.enter_description') }}" rows="4"
            name="description" cols="50">
@isset($tag->description)
{{ $tag->description }}
@endisset
</textarea>
        @error('description')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<input type="hidden" name="type" value="blog">

<div class="form-group row">
    <label class="col-md-2" for="role">{{ __('static.status') }}</label>
    <div class="col-md-10">
        <div class="editor-space">
            <label class="switch">
                @if (isset($tag))
                    <input class="form-control" type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="" value="1"
                        {{ $tag->status ? 'checked' : '' }}>
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
                $("#tagForm").validate({
                    ignore: [],
                    rules: {
                        "name": "required",
                        "description": "required",
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
