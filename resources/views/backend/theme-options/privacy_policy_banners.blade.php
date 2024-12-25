<div class="accordion-item" id="banner-{{ $index }}">
    <h2 class="accordion-header" id="heading-{{ $index }}">
        <button class="accordion-button  @if($index != 0) 'collapsed' @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $index }}" aria-expanded="@if($index != 0) 'true' @else 'false' @endif">
            {{ $banner['title'] ?? 'New Banner' }}
        </button>
    </h2>
    <div id="collapse-{{ $index }}" class="accordion-collapse collapse @if($index == 0) 'show' @endif" aria-labelledby="heading-{{ $index }}">
        <div class="accordion-body">
            <div class="form-group row">
                <label class="col-md-2">{{ __('Title') }}</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="privacy_policy[banners][{{ $index }}][title]" value="{{ $banner['title'] ?? '' }}" placeholder="Enter banner title">
                </div>
            </div>
            <div class="form-group row">
                <label for="breadcrumb_description" class="col-md-2">{{ __('Description') }}</label>
                <div class="col-md-10">
                    <textarea class="form-control summary-ckeditor" name="privacy_policy[banners][{{ $index }}][description]" id="privacy_policy_banners___INDEX___description" placeholder="{{ __('static.theme_options.enter_description') }}" rows="2">{{ $banner['description'] ?? '' }}</textarea>
                </div>
            </div>
            <button type="button" class="btn btn-danger remove-banner">Remove</button>
        </div>
    </div>
</div>

@push('js')
<script>
        $(document).ready(function() {
            "use strict";

            let bannerIndex = {{ count($themeOptions['privacy_policy']['banners'] ?? []) }};

            // Add a new banner
            $('#add_privacy_policy_Banner').click(function() {
                const template = $('#privacy_policy_bannerTemplate').html().replace(/__INDEX__/g, bannerIndex);
                $('#privacy_policy_BannersAccordion').append(template);
            
                tinymce.init({
                    selector: '#privacy_policy_banners_' + bannerIndex + '_description',  // Unique selector for each textarea        plugins: 'lists link image',  // Add any plugins you need
                    toolbar: [
                        'newdocument | print preview | searchreplace | undo redo  | alignleft aligncenter alignright alignjustify | code',
                        'formatselect fontselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor',
                        'removeformat | hr pagebreak | charmap subscript superscript insertdatetime | bullist numlist | outdent indent blockquote | table'
                    ],
                    plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste imagetools"
                ],
                menubar: false,
                image_title: true,
                automatic_uploads: true,
                file_picker_types: 'image',
                relative_urls: false,
                remove_script_host: false,
                convert_urls: false,
                branding: false,
            });

                bannerIndex++;
            });

            // Remove a banner
            $(document).on('click', '.remove-banner', function() {
                $(this).closest('.accordion-item').remove();
            });
        });
    </script>
@endpush
