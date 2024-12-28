@use('App\Enums\ServiceTypeEnum')
@use('app\Helpers\Helpers')

@php
    $homePage = Helpers::getCurrentHomePage();
@endphp

<!-- Extend App Layout -->
@extends('frontend.layout.master')

<!-- Page Title -->
@section('title', __('frontend::static.becomeAffiliate.title'))

@section('breadcrumb')
<nav class="breadcrumb breadcrumb-icon">
    <li>
        <a class="breadcrumb-item" href="{{ url('/') }}">Home</a>
        <span class="breadcrumb-item active">{{ __('frontend::static.becomeAffiliate.title') }}</span>
    </li>
</nav>
@endsection

@section('content')
    <div class="container" style="margin-top: 100px;">
        <div class="row">
            <div class="col-md-12">
                <h1>Become an Affiliate</h1>
                <p>Fill out the form below to become an affiliate.</p>
            </div>
        </div>
    </div>

    <script>
        console.log('@json(__("frontend::static.categories"))');
    </script>
@endsection
