    <div class="accordion-item" id="banner-__INDEX__">
        <h2 class="accordion-header" id="heading-__INDEX__">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-__INDEX__" aria-expanded="true">
            {{ $banner['title'] ?? '' }}
            </button>
        </h2>
        <div id="collapse-__INDEX__" class="accordion-collapse collapse show" aria-labelledby="heading-__INDEX__">
            <div class="accordion-body">
                <div class="form-group row">
                    <label class="col-md-2">{{ __('Title') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="terms_and_conditions[banners][__INDEX__][title]" id="banner_title___INDEX__" placeholder="Enter banner title" value="{{ $banner['title'] ?? '' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="breadcrumb_description" class="col-md-2">{{ __('Description') }}</label>
                    <div class="col-md-10">
                        <textarea class="form-control summary-ckeditor" name="terms_and_conditions[banners][__INDEX__][description]" id="banner_description___INDEX__" placeholder="{{ __('Enter description') }}" rows="2">{{ $banner['description'] ?? '' }}</textarea>
                    </div>
                </div>
                <button type="button" class="btn btn-danger remove-banner">Remove</button>
            </div>
        </div>
    </div>

