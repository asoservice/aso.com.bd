<div class="form-group row">
    <label class="col-md-2" for="title">{{ __('static.title') }}<span> *</span></label>
    <div class="col-md-10">
        <input type="hidden" name="category_type" value="service">
        <input class="form-control" type="text" name="title" placeholder="{{  __('static.categories.enter_title')}}"
            value="{{ isset($cat->title) ? $cat->title : old('title') }}">
        @error('title')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="description" class="col-md-2">{{ __('static.categories.description') }}<span> *</span></label>
    <div class="col-md-10">
        <textarea class="form-control" placeholder="{{ __('static.categories.enter_description') }}" rows="4" name="description" cols="50">{{ $cat->description ?? old('description') }}</textarea>
        @error('description')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2" for="commission">{{ __('static.categories.commission') }}<span> *</span></label>
    <div class="col-md-10">
        <div class="input-group mb-3 flex-nowrap">
            <div class="w-100 percent">
                <input class='form-control' id="commission" type="number" name="commission" min="1" value="{{ $cat->commission ?? old('commission') }}" placeholder="{{ __('static.categories.enter_commission') }}" oninput="if (value > 100) value = 100; if (value < 0) value = 0;">
            </div>
                <span class="input-group-text">%</span>
                @error('commission')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2" for="parent_id">{{ __('static.categories.parent') }}</label>
    <div class="col-md-10 error-div select-dropdown">
        <select class="select-2 form-control" name="parent_id" data-placeholder="{{ __('static.categories.parent_category') }}">
            <option class="select-placeholder" value=""></option>
                {{-- @dd($allparent) --}}
            @foreach ($allparent as $key => $option)
                @if($key != @$cat->id)
                    <option class="option" value="{{ $key }}" @if (old('parent_id', isset($cat) ? $cat->parent_id : '') == $key) selected @endif>{{ $option }}</option>
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
                    @foreach ($cat?->media as $media)
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
    <label class="col-md-2" for="zones">{{ __('static.zone.zones') }}<span> *</span> </label>
    <div class="col-md-10 error-div select-dropdown">
        <select id="blog_zones" class="select-2 form-control" id="zones[]" search="true"
            name="zones[]" data-placeholder="{{ __('static.zone.select-zone') }}" multiple>
            <option></option>
            @foreach ($zones as $key => $value)
                <option value="{{ $key }}"
                    {{ (is_array(old('zones')) && in_array($key, old('zones'))) || (isset($default_zones) && in_array($key, $default_zones)) ? 'selected' : '' }}>
                    {{ $value }}</option>
            @endforeach
        </select>
        @error('zones')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2" for="status">{{ __('static.status') }}</label>
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
<div class="form-group row">
    <label class="col-md-2" for="is_featured">{{ __('static.categories.is_featured') }}</label>
    <div class="col-md-10">
        <div class="editor-space">
            <label class="switch">
                @if (isset($cat))
                    <input class="form-control" type="hidden" name="is_featured" value="0">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="" value="1"
                        {{ $cat->is_featured ? 'checked' : '' }}>
                @else
                    <input class="form-control" type="hidden" name="is_featured" value="0">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="" value="1">
                @endif
                <span class="switch-state"></span>
            </label>
        </div>
    </div>
</div>
@push('js')
<script src="{{ asset('admin/js/jstree.min.js') }}"></script>
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
                    "zones[]": "required",
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
            var tree_custom = {
                init: function() {
                    $('#treeBasic').jstree({
                        'core': {
                            'themes': {
                                'responsive': false,
                            },
                        },
                        'types': {
                            'default': {
                                'icon': 'ti-gallery'
                            },
                            'file': {
                                'icon': 'ti-file'
                            }
                        },
                        "search": {
                            "case_insensitive": true,
                            "show_only_matches": true
                        },
                        'plugins': ['types', 'search']
                    });

                    $('#search').keyup(function() {
                        $('#treeBasic').jstree('search', $(this).val());
                    });

                    $(document).on('click', '.edit-icon', function(e) {
                        var categoryId = $(this).attr('value');
                        var url = "{{ route('backend.category.edit', ':id') }}";
                        url = url.replace(':id', categoryId);
                        window.location.href = url;
                    });

                    $(document).on('click', '.edit-child', function(e) {
                        var categoryId = $(this).attr('value');
                        var url = "{{ route('backend.category.edit', ':id') }}";
                        url = url.replace(':id', categoryId);
                        window.location.href = url;
                    });
                }
            };
            $(document).ready(function() {
                tree_custom.init();

                setTimeout(function() {
                    $('.jstree-loader').fadeOut('slow');
                    $('#treeBasic').show();
                }, 500);
            });    
    })(jQuery);
</script>
@endpush
