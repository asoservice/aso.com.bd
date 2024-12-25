@use('App\Helpers\Helpers')
@use('App\Models\Zone')
@use('App\Models\Language')
@php
$zones = Zone::where('status', true)->pluck('name', 'id');
$countries = Helpers::getCountries();
$languages = Language::get();
@endphp

@extends('auth.master')

@section('title', __('Register'))

@section('content')
<section class="auth-page" style="background-image: url('/admin/images/login-bg.png')">
    <div class="container">
        <div class="row">
            <div class="col-xxl-5 col-xl-6 col-lg-8 ms-auto">
                <div class="auth-card">
                    <div class="text-center">
                        <img class="login-img" src="{{ $settings['general']['dark_logo'] ?? asset('admin/images/logo-dark.png') }}">
                    </div>
                    <div class="welcome">
                        <h3>{{ __('Become a Provider') }}</h3>
                        <p>{{ __('static.sign_in_note') }}</p>
                    </div>
                    @if ($errors->any())
                    <div class="error-note" id="errors">
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                        <i data-feather="x" class="close-errors"></i>
                    </div>
                    @endif
                    <div class="main">
                    <form action="{{ route('become-provider.store') }}" id="providerForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('auth.become-provider.fields')
                    </form>
                    @include('auth.become-provider.address')
                    </div>
                    @isset($settings['activation']['default_credentials'])
                    @if($settings['activation']['default_credentials'])
                    <div class="demo-credential">
                        <button class="btn btn-solid default-credentials" data-email="admin@example.com">Admin</button>
                        <button class="btn btn-solid default-credentials" data-email="provider@example.com">Provider</button>
                        <button class="btn btn-solid default-credentials" data-email="servicemen@example.com">Serviceman</button>
                    </div>
                    @endif
                    @endisset
                </div>
            </div>
        </div>
        <div class="animate-object">
            <img class="vase-img" src="{{ asset('admin/images/vase.png') }}">
            <img class="girl-img" src="{{ asset('admin/images/girl.png') }}">
            <img class="lamp-img" src="{{ asset('admin/images/lamp.png') }}">
            <div class="clockbox">
                <svg id="clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600">
                    <g id="face">
                        <circle class="circle" cx="300" cy="300" r="253.9"></circle>
                        <path class="hour-marks"
                            d="M300.5 94V61M506 300.5h32M300.5 506v33M94 300.5H60M411.3 107.8l7.9-13.8M493 190.2l13-7.4M492.1 411.4l16.5 9.5M411 492.3l8.9 15.3M189 492.3l-9.2 15.9M107.7 411L93 419.5M107.5 189.3l-17.1-9.9M188.1 108.2l-9-15.6">
                        </path>
                        <circle class="mid-circle" cx="300" cy="300" r="16.2"></circle>
                    </g>
                    <g id="hour">
                        <path class="hour-hand" d="M300.5 298V142"></path>
                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                    </g>
                    <g id="minute">
                        <path class="minute-hand" d="M300.5 298V67"> </path>
                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                    </g>
                    <g id="second">
                        <path class="second-hand" d="M300.5 350V55"></path>
                        <circle class="sizing-box" cx="300" cy="300" r="253.9"> </circle>
                    </g>
                </svg>
            </div>
        </div>
    </div>
</section>
@endsection


@push('js')
<script>
    $(document).ready(function(){
        $(".close-errors").click(function () {  
            $("#errors").remove();  
        });

        $("#loginForm").validate({
            ignore: [],
            rules: {
                "email": "required",
                "password": "required",
            }
        });

        $(".default-credentials").click(function () { 
            $("#email-input").val("");
            $("#password-input").val(""); 
            var email = $(this).data("email");
            $("#email-input").val(email);
            $("#password-input").val("123456789");
        });
    });
</script>
@endpush

