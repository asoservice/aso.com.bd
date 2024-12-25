<div class="form-group row">
    <label class="col-md-2" for="title">{{ __('static.title') }}<span> *</span></label>
    <div class="col-md-10">
        <input type="hidden" name="category_type" value="blog">
        <input class='form-control' type="text" name="title" value="{{ isset($cat->title) ? $cat->title : old('title') }}" placeholder="{{ __('static.blog.enter_title') }}">
        @error('title')
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="address" class="col-md-2">{{ __('static.categories.description') }}<span> *</span></label>
    <div class="col-md-10">
        <textarea class="form-control" rows="4" name="description" placeholder="{{ __('static.categories.enter_description') }}" cols="50">{{ isset($cat->description) ? $cat->description : old('description') }}</textarea>
        @error('description')
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2" for="parent_id">{{ __('static.categories.parent') }}</label>
    <div class="col-md-10 error-div select-dropdown">
        <select class="select-2 form-control" name="parent_id" data-placeholder="{{ __('static.categories.parent_category') }}">
            <option class="select-placeholder" value=""></option>
            @foreach ($allparent as $key => $option)
            @if($key != @$cat->id)
            <option class="option" value="{{ $key }}" @if (old('parent_id', isset($cat) ? $cat->parent_id : '') == $key) selected @endif>
                {{ $option }}
            </option>
            @endif
            @endforeach
        </select>
        @error('parent_id')
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="image" class="col-md-2">{{ __('static.provider.image') }}</label>
    <div class="col-md-10">
        <input class='form-control' type="file" accept=".jpg, .png, .jpeg" id="image" name="image">
        @error('image')
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
