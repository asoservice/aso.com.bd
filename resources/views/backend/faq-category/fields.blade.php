<div class="form-group row">
    <label class="col-md-2" for="name">{{ __('Name') }}<span> *</span></label>
    <div class="col-md-10">
        <input class='form-control' required type="text" name="name" value="{{ isset($cat->name) ? $cat->name : old('name') }}" placeholder="{{ __('static.blog.enter_title') }}">
        @error('name')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2" for="sort_order">{{ __('Sort Order') }} </label>
    <div class="col-md-10">
        <input class='form-control' type="number" name="sort_order" value="{{ isset($cat->sort_order) ? $cat->sort_order : old('sort_order') }}" placeholder="{{ __('static.blog.enter_title') }}">
        @error('sort_order')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="address" class="col-md-2">{{ __('Description') }} </label>
    <div class="col-md-10">
        <textarea class="form-control" rows="4" name="description" placeholder="{{ __('Description') }}" cols="50">{{ isset($cat->description) ? $cat->description : old('description') }}</textarea>
        @error('description')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label for="icon" class="col-md-2">{{ __('Icon') }}</label>
    <div class="col-md-10">
        <input class='form-control' type="file" accept=".jpg, .png, .jpeg" id="icon" name="icon">
        @error('icon')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

@if (isset($cat))
<div class="form-group">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <div class="image-list">
                @foreach ($cat->media as $media)
                    <div class="image-list-detail">
                        <div class="position-relative">
                            <img src="{{ $media['original_url'] }}" id="{{ $media['id'] }}" alt="User Image"
                                class="image-list-item">
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
    <label class="col-md-2" for="role">{{ __('static.status') }}</label>
    <div class="col-md-10">
        <div class="editor-space">
            <label class="switch">
                @if (isset($cat))
                <input class="form-control" type="hidden" name="status" value="0">
                <input class="form-check-input" type="checkbox" name="status" id="" value="1"
                    {{ $cat->status ? 'checked' : '' }}>
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
            $("#categoryForm").validate({
                ignore: [],
                rules: {
                    "title": "required",
                    "description": "required",
                    "commission": "required",
                    "content": "required",
                    "image": {
                        accept: "image/jpeg, image/png"
                    },
                },
                messages: {
                    "image": {
                        accept: "Only JPEG and PNG files are allowed.",
                    },
                }
            });
        });
    })(jQuery);
</script>
@endpush
