<div class="form-group row">
    <label class="col-md-2" for="title">{{ __('static.title') }}<span> *</span></label>
    <div class="col-md-10">
        <input class='form-control' type="text" name="title" id="title"
            value="{{ isset($page->title) ? $page->title : old('title') }}"
            placeholder="{{ __('static.page.enter_title') }}">
        @error('title')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>


<div class="form-group row">
    <label for="image" class="col-md-2">{{ __('static.page.content') }}<span> *</span></label>
    <div class="col-md-10">
        <textarea class="summary-ckeditor" id="content" name="content" cols="65" rows="5">{{ isset($page->content) ? $page->content : old('content') }}</textarea>
        @error('content')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label for="app_icon" class="col-md-2">{{ __('static.page.app_icon') }}<span> *</span></label>
    <div class="col-md-10">
        <input class='form-control' type="file" id="app_icon" name="app_icon">
        @error('app_icon')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

@if (isset($page) && isset($page->getFirstMedia('app_icon')->original_url))
    <div class="form-group">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <div class="image-list">
                    <div class="image-list-detail">
                        <div class="position-relative">
                            <img src="{{ $page->getFirstMedia('app_icon')->original_url }}" id="{{ $page->getFirstMedia('app_icon')->id }}" alt="{{ $page->getFirstMedia('app_icon')->name }}" class="image-list-item">
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
    <label class="col-md-2" for="metatitle">{{ __('static.page.meta_title') }}<span> *</span></label>
    <div class="col-md-10">
        <input class='form-control' type="text" name="metatitle" id="metatitle"
            value="{{ isset($page->meta_title) ? $page->meta_title : old('metatitle') }}"
            placeholder="{{ __('static.page.placeholder_meta_title') }}">
        @error('metatitle')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class = "form-group row">
    <label for="address" class="col-md-2">{{ __('static.page.meta_descripation') }}</label>
    <div class="col-md-10">
        <textarea class = "form-control" id="metadescription" rows="4" placeholder="{{ __('static.pages.meta_description') }}" name="metadescription" cols="50">{{ isset($page->meta_description) ? $page->meta_description : old('metadescription') }}</textarea>
        @error('metadescription')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <label for="meta_image" class="col-md-2">{{ __('static.page.meta_image') }}</label>
    <div class="col-md-10">
        <input class='form-control' type="file" id="meta_image" name="meta_image">
        @error('meta_image')
            <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
@if (isset($page) && isset($page->getFirstMedia('meta_image')->original_url))
    <div class="form-group">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <div class="image-list">
                    <div class="image-list-detail">
                        <div class="position-relative">
                            <img src="{{ $page->getFirstMedia('meta_image')->original_url }}" id="{{ $page->getFirstMedia('meta_image')->id }}" alt="{{ $page->getFirstMedia('meta_image')->name }}" class="image-list-item">
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
    <label class="col-md-2" for="role">{{ __('static.status') }}</label>
    <div class="col-md-10">
        <div class="editor-space">
            <label class="switch">
                @if (isset($page))
                    <input class="form-control" type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="" value="1"
                        {{ $page->status ? 'checked' : '' }}>
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
                $("#pageForm").validate({
                    ignore: [],
                    rules: {
                        "title": "required",
                        "content": "required",
                        "metatitle": "required",
                        "metadescription": "required",
                    },
                });
            });
            tinymce.init({
                selector: '.summary-ckeditor',
                image_class_list: [{
                    title: 'Responsive',
                    value: 'img-fluid'
                }, ],

                width: '100%',
                height: 350,
                setup: function(editor) {
                    editor.on('init change', function() {
                        editor.save();
                    });
                },
                plugins: [
                    "advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste imagetools"
                ],
                toolbar: [
                    'newdocument | print preview | searchreplace | undo redo  | alignleft aligncenter alignright alignjustify | code',
                    'formatselect fontselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor',
                    'removeformat | hr pagebreak | charmap subscript superscript insertdatetime | bullist numlist | outdent indent blockquote | table'
                ],
                menubar: false,
                image_title: true,
                automatic_uploads: true,
                file_picker_types: 'image',
                relative_urls: false,
                remove_script_host: false,
                convert_urls: false,
                branding: false,
                file_picker_callback: function(cb, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function() {
                        var file = this.files[0];

                        var reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = function() {
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];
                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), {
                                title: file.name
                            });
                        };
                    };
                    input.click();
                },
                placeholder: 'Enter your text here...',
            });
        })(jQuery);
    </script>
@endpush
