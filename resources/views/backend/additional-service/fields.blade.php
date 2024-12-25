@use('app\Helpers\Helpers')
<div class="form-group row">
    <label for="thumbnail" class="col-md-2">{{ __('static.categories.thumbnail') }}<span> *</span></label>
    <div class="col-md-10">
        <input class="form-control" type="file" id="thumbnail" accept=".jpg, .png, .jpeg" name="thumbnail">
        @error('thumbnail')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
@if(isset($additionalService) && isset($additionalService->getFirstMedia('thumbnail')->original_url))
    <div class="form-group">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <div class="image-list">
                    <div class="image-list-detail">
                        <div class="position-relative">
                            <img src="{{ $additionalService->getFirstMedia('thumbnail')->original_url }}"
                                id="{{ $additionalService->getFirstMedia('thumbnail')->id }}" alt="User Image"
                                class="image-list-item">
                            <div class="close-icon">
                                <i data-feather="x"></i>                                                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="form-group row">
    <label class="col-md-2" for="parent_id">{{ __('static.service.services') }}<span> *</span></label>
    <div class="col-md-10 error-div select-dropdown">
        <select class="select-2 form-control user-dropdown" id="parent_id" name="parent_id" data-placeholder="{{ __('static.additional_service.select_service') }}">
            <option class="select-placeholder" value=""></option>
            @foreach ($services as $key => $option)
                <option value="{{ $option->id }}"
                                image="{{ $option->getFirstMedia('image')?->getUrl() }}"
                                        @if (old('parent_id', isset($additionalService) ? $additionalService->parent_id : '') == $option->id) selected @endif>
                                {{ $option->title }}
                            </option>
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
    <label class="col-md-2" for="title">{{ __('static.title') }}<span> *</span></label>
    <div class="col-md-10">
        <input class='form-control' type="text" id="title" name="title" value="{{ isset($additionalService->title) ? $additionalService->title : old('title') }}" placeholder="{{ __('static.service.enter_title') }}">
        @error('title')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

@hasrole('provider')
    <input type="hidden" name="provider_id" value="{{ auth()->user()->id }}" id="provider_id">
@endhasrole

<div class="form-group row">
    <label class="col-md-2" for="price">{{ __('static.service.price') }}<span> *</span></label>
    <div class="col-md-10 error-div">
        <div class="input-group mb-3 flex-nowrap">
            <span class="input-group-text">{{Helpers::getSettings()['general']['default_currency']->symbol}}</span>
            <div class="w-100">
                <input class='form-control' type="number" id="price" name="price" min="1" value="{{ isset($additionalService->price) ? $additionalService->price : old('price') }}" placeholder="{{ __('static.coupon.price') }}">
                @error('price')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2" for="role">{{ __('static.status') }}</label>
    <div class="col-md-10">
        <div class="editor-space">
            <label class="switch">
                @if (isset($additionalService))
                    <input class="form-control" type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="" value="1"
                        {{ $additionalService->status ? 'checked' : '' }}>
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
                $("#additionalServiceForm").validate({
                    ignore: [],
                    rules: {
                        "thumbnail": {
                            required: isServiceImage,
                            accept: "image/jpeg, image/png"
                        },
                        "title": "required",
                        "price": "required",
                        "parent_id":{
                            required: true
                        },
                    }
                });
            });

            function isServiceImage() {
                @if (isset($additionalService->media) && !$additionalService->media->isEmpty())
                    return false;
                @else
                    return true;
                @endif
            }
        })(jQuery);
    </script>
@endpush
