@use('App\Models\Setting')
@php
$settings = Setting::pluck('values')?->first();
@endphp

<!-- footer start-->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 footer-copyright d-flex align-items-center">
                <p class="mb-0">{{ $settings['general']['copyright'] ?? '' }}</p>
            </div>
            @if (env('APP_VERSION'))
            <div class="col-md-6">
                <span class="ms-auto me-md-0 me-auto d-flex mt-md-0 mt-3 badge badge-version-primary">{{ __('static.version') }}: {{ env('APP_VERSION') }}</span>
            </div>
            @endif
        </div>
    </div>
</footer>
<!-- footer end-->
