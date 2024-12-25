@use('App\Models\Zone')
@php
    $zones = Zone::where('status', true)->pluck('name', 'id')
@endphp
<div class="form-group row">
    <label class="col-md-2" for="title">{{ __('static.title') }}<span> *</span></label>
    <div class="col-md-10">
        <input class="form-control" id="title" type="text" name="title"
            value="{{ isset($banner->title) ? $banner->title : old('title') }}"
            placeholder="{{ __('static.banner.enter_title') }}">
        @error('title')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label for="image" class="col-md-2">{{ __('static.banner.image') }}<span> *</span></label>
    <div class="col-md-10">
        <input class="form-control" type="file" id="images" name="images[]" multiple>
        @error('images.*')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
@isset($banner->media)
    <div class="form-group">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <div class="image-list">
                    @foreach ($banner->media as $media)
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
@endisset
<div class="form-group row">
    <label class="col-md-2" for="type">{{ __('static.banner.type') }}<span> *</span></label>
    <div class="col-md-10 error-div select-dropdown">
        <select class="select-2 form-control banner_type" name="type" id="type"
            data-placeholder="{{ __('static.banner.select_type') }}">
            <option class="select-placeholder" value=""></option>
            @foreach ($bannerType as $key => $option)
                <option class="option" value="{{ $key }}" @if (isset($banner)) @if ($key == $banner->type) selected @endif @endif>{{ $option }}</option>
            @endforeach
        </select>
        @error('type')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2" for="related_id">{{ __('static.banner.category') }}<span> *</span></label>
    <div class="col-md-10 error-div select-dropdown">
        <select class="select-2 form-control banner_category" name="related_id" id="related_id"
            data-placeholder="{{ __('static.banner.category_type') }}">
            <option class="select-placeholder" value=""></option>
        </select>
        @error('related_id')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-md-2" for="zones">{{ __('static.zone.zones') }}<span> *</span> </label>
    <div class="col-md-10 error-div select-dropdown">
        <select id="blog_zones" class="select-2 form-control" id="zones[]" search="true"
            name="zones[]" data-placeholder="{{ __('static.zone.select-zone') }}" multiple>
            <option></option>
            @foreach ($zones as $key => $value)
                <option value="{{ $key }}" {{ (is_array(old('zones')) && in_array($key, old('zones'))) || (isset($banner->zones) && in_array($key, $banner->zones->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
        @error('zones.*')
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
                @if (isset($banner))
                    <input class="form-control" type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="" value="1"
                        {{ $banner->status ? 'checked' : '' }}>
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
    <label class="col-md-2" for="role">{{ __('static.banner.is_offer') }}</label>
    <div class="col-md-10">
        <div class="editor-space">
            <label class="switch">
                @if (isset($banner))
                    <input class="form-control" type="hidden" name="is_offer" value="0">
                    <input class="form-check-input" type="checkbox" name="is_offer" id="" value="1"
                        {{ $banner->is_offer ? 'checked' : '' }}>
                @else
                    <input class="form-control" type="hidden" name="is_offer" value="0">
                    <input class="form-check-input" type="checkbox" name="is_offer" id="" value="1"
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
            var isImagesRequired = <?php echo (isset($banner->media) && !$banner->media->isEmpty()) ? 'false' : 'true'; ?>;

            $("#bannerForm").validate({
                ignore: [],
                rules: {
                    "title": "required",
                    "type": "required",
                    "related_id": "required",
                    "zones[]": "required",
                    "images[]": {
                        required: isImagesRequired,
                    },
                },
            }); 

            var initialBannerType = $(".banner_type").val();
            var initialRelatedId = "{{ isset($banner->related_id) ? $banner->related_id : '' }}";
            if (initialBannerType) {
                loadBannerCategories(initialBannerType, initialRelatedId);
            }

            $('.banner_type').on('change', function() {
                var banner_type = this.value;
                $(".banner_category").html('');
                loadBannerCategories(banner_type, '');
            });

            function loadBannerCategories(banner_type, selectedCategory) {
                $.ajax({
                    url: "{{ url('/backend/bannerCategory') }}",
                    type: "POST",
                    data: {
                        bannerType: banner_type,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $.each(result.bannerCategory, function(key, value) {
                            var selected = (value.id == selectedCategory) ? 'selected' : '';
                            if (value.name) {
                                $(".banner_category").append('<option value="' + value.id + '" ' + selected + '>' + value.name +'</option>');
                            } else {
                                $(".banner_category").append('<option value="' + value.id + '" ' + selected + '>' + value.title +'</option>');
                            }
                        });
                    }
                });
            }
        });
    })(jQuery);
</script>
@endpush
