<div class="form-group row">
    <label class="col-md-2" for="title">{{ __('static.title') }}<span> *</span></label>
    <div class="col-md-10">
        <input class='form-control' type="text" name="title" id="title"
            value="{{ isset($blog->title) ? $blog->title : old('title') }}"
            placeholder="{{ __('static.blog.enter_title') }}">
        @error('title')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="address" class="col-md-2">{{ __('static.blog.description') }}<span> *</span></label>
    <div class="col-md-10">
        <textarea class = "form-control" rows="4" name="description" id="description"
            placeholder="{{ __('static.blog.enter_description') }}" cols="50"
            placeholder="{{ __('static.blog.enter_description') }}">{{ isset($blog->description) ? $blog->description : old('description') }}</textarea>
        @error('description')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="address" class="col-md-2">{{ __('Content') }}<span> *</span></label>
    <div class="col-md-10 d-flex flex-column-reverse">
        <textarea class = "form-control summary-ckeditor" id="content" rows="4" name="content" cols="50">{{ isset($blog->content) ? $blog->content : old('content') }}</textarea>
        @error('content')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="image[]" class="col-md-2">{{ __('static.blog.image') }}<span> *</span></label>
    <div class="col-md-10">
        <input class="form-control" type="file" accept=".jpg, .png, .jpeg" id="image[]" name="image[]" multiple>
        @error('image')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
@if (isset($blog->media) && !$blog->getMedia('image')->isEmpty())    
<div class="form-group">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <div class="image-list">
                @foreach ($blog->getMedia('image') as $media)    
                <div class="image-list-detail">
                    <div class="position-relative">
                        <img src="{{ $media['original_url'] }}" id="{{ $media['id'] }}" alt="User Image" class="image-list-item">
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
    <label for="web_image[]" class="col-md-2">{{ __('static.blog.web_image') }}<span> *</span></label>
    <div class="col-md-10">
        <input class="form-control" type="file" id="web_image[]" name="web_image[]" multiple>
        @error('web_image')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
@if (isset($blog->media) && !$blog->getMedia('web_image')->isEmpty())    
<div class="form-group">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-10">
            <div class="image-list">
                @foreach ($blog->getMedia('web_image') as $media)    
                <div class="image-list-detail">
                    <div class="position-relative">
                        <img src="{{ $media['original_url'] }}" id="{{ $media['id'] }}" alt="User Image" class="image-list-item">
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
    <label class="col-md-2" for="meta_title">{{ __('static.blog.meta_title') }}<span> *</span></label>
    <div class="col-md-10">
        <input class='form-control' type="text" name="meta_title" id="meta_title"
            value="{{ isset($blog->meta_title) ? $blog->meta_title : old('meta_title') }}"
            placeholder="{{ __('static.blog.enter_meta_title') }}">
        @error('meta_title')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="address" class="col-md-2">{{ __('static.blog.meta_description') }}<span> *</span></label>
    <div class="col-md-10">
        <textarea class = "form-control" rows="4" placeholder="{{ __('static.blog.enter_meta_description') }}"
            id="meta_description" name="meta_description" cols="50">{{ isset($blog->meta_description) ? $blog->meta_description : old('meta_description') }}</textarea>
        @error('meta_description')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="image" class="col-md-2">{{ __('static.page.meta_image') }}<span> *</span></label>
    <div class="col-md-10">
        <input class="form-control" type="file" accept=".jpg, .png, .jpeg" id="meta_image" name="meta_image"
            multiple>
        @error('image')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
@if(isset($blog) && isset($blog->getFirstMedia('meta_image')->original_url))
    <div class="form-group">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <div class="image-list">
                    <div class="image-list-detail">
                        <div class="position-relative">
                            <img src="{{ $blog->getFirstMedia('meta_image')->original_url }}" id="{{ $blog->getFirstMedia('meta_image')->id }}" alt="Meta Image" class="image-list-item">
                            <div class="close-icon">
                                <i data-feather="x"></i>                                                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endisset
<div class="form-group row">
    <label class="col-md-2" for="categories">{{ __('static.blog.category') }}<span> *</span> </label>
    <div class="col-md-10 error-div select-dropdown">
        <select id="blog_categories" class="select-2 form-control" id="categories[]" search="true"
            name="categories[]" data-placeholder="{{ __('static.categories.select-categories') }}" multiple>
            <option></option>
            @foreach ($categories as $key => $value)
                <option value="{{ $key }}"
                    {{ (is_array(old('categories')) && in_array($key, old('categories'))) || (isset($default_categories) && in_array($key, $default_categories)) ? 'selected' : '' }}>
                    {{ $value }}</option>
            @endforeach
        </select>
        @error('categories')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2" for="tags[]">{{ __('static.blog.tags') }}</label>
    <div class="col-md-10 error-div select-dropdown">
        <select id="tags[]" class="select-2 select-search form-control" search="true"
            data-placeholder="{{ __('static.tag.select_tags') }}" name="tags[]" multiple="multiple">
            <option></option>
            @foreach ($tags as $key => $value)
                <option value="{{ $key }}"
                    {{ (is_array(old('tags')) && in_array($key, old('tags'))) || (isset($default_tags) && in_array($key, $default_tags)) ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('tags')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2" for="role">{{ __('static.status') }}</label>
    <div class="col-md-10">
        <div class="editor-space">
            <label class="switch">
                @if (isset($blog))
                    <input class="form-control" type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="" value="1"
                        {{ $blog->status ? 'checked' : '' }}>
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
<div class="form-group row">
    <label class="col-md-2" for="role">{{ __('static.service.is_featured') }}</label>
    <div class="col-md-10">
        <div class="editor-space">
            <label class="switch">
                @if (isset($blog))
                    <input class="form-control" type="hidden" name="is_featured" value="0">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                        {{ $blog->is_featured ? 'checked' : '' }}>
                @else
                    <input class="form-control" type="hidden" name="is_featured" value="0">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1">
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
                $("#blogForm").validate({
                    ignore: [],
                    rules: {
                        "title": "required",
                        "description": "required",
                        "content": "required",
                        "meta_title": "required",
                        "meta_description": "required",
                        "discount": "required",
                        "image[]": {
                            required: isBlogImages,
                            accept: "image/jpeg, image/png"
                        },
                        "meta_image": {
                            required: isBlogImages,
                            accept: "image/jpeg, image/png"
                        },
                        "categories[]": "required",
                    },
                    messages: {
                        "image[]": {
                            accept: "Only JPEG and PNG files are allowed.",
                        },
                        "meta_image": {
                            accept: "Only JPEG and PNG files are allowed.",
                        },
                    }
                });

                function isBlogImages() {
                    @if (isset($blog->media) && !$blog->media->isEmpty())
                        return false;
                    @else
                        return true;
                    @endif
                }
                function isBlogMetaImage() {
                    @if (isset($blog->meta_image) && !$blog->meta_image->isEmpty())
                        return false;
                    @else
                        return true;
                    @endif
                }
            });
        })(jQuery);
    </script>
@endpush
