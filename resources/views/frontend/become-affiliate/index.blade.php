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

@push('css')
<style>
    .breadcrumb-section {
    padding: 50px !important;
}
</style>
@endpush



@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="hero__item set-bg">
                    <div class="hero__text">
                        <h2>Become an aso Affiliate</h2>
                        <p>Join the aso Affiliate Program and start earning by recommending service provider and their service.</p>
                        @if(Auth::check())
                            @php
                                $user = App\Models\User::find(Auth::user()->id);
                                $checkRole = $user->hasRole('Marketer');
                            @endphp
                            @if($checkRole)
                                <a href="{{ route('affiliate.dashboard') }}" class="primary-btn">Go To Dashboard</a>
                            @endif
                        @endif
                        @if(isset($checkRole))

                        @else
                        <a href="{{route('frontend.becomeAffiliate.join')}}" class="primary-btn">Join Now</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img src="{{ asset('frontend/images/ca7ed2fbe285a7f36b18055a88c029e5.png') }}" alt="" class="img-fluid">
            </div>
        </div>
    </div>
    <div class="container-fluid sticky" style="border-bottom: 1px solid #dee2e6; border-top: 1px solid #dee2e6;">
        <div class="row">
            <div class="content-nav">
                <div class="content-nav-flex">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#aso_assosiate"><button class="nav-link active" id="home-tab" type="button" aria-selected="true">aso Affiliate</button></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#faq"><button class="nav-link" id="profile-tab" type="button" aria-selected="false">FAQ</button></a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#income"><button class="nav-link" id="contact-tab" type="button" aria-selected="false">Income</button></a>
                        </li>
                        <li class="nav_button">
                            @if(Auth::check())
                            @php
                                $user = App\Models\User::find(Auth::user()->id);
                                $checkRole = $user->hasRole('Marketer');
                            @endphp
                            @if($checkRole)
                            <a href="{{ route('affiliate.dashboard') }}" class="primary-btn">Go To Dashboard</a>
                            @endif
                            @endif

                            @if(isset($checkRole))
                            @else
                            <a href="{{route('frontend.becomeAffiliate.join')}}" class="primary-btn">Join Now</a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="container pt-3 pb-3" id="aso_assosiate">
            <div class="content-banner">
                <img src="{{ asset('frontend/images/95b7e99a93b1e8db81d03ad43cf02ea5.png') }}" alt="" class="img-fluid">
                <img src="{{ asset('frontend/images/410f99d22b802fb17f3cd457bdede19f.png') }}" alt="" class="img-fluid">
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="tab_text">
                        <h3>aso Affiliate Program in Bangladesh: <br>
                        Earn Money Online With aso</h3>
                        <p>admin will add this text from admin panel</p>
                    </div>
                </div>
                <div class="col-md-5">
                    <img src="{{ asset('frontend/images/ca7ed2fbe285a7f36b18055a88c029e5.png') }}" alt="" class="img-fluid">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tab_text">
                        <h3>Affiliate Agreement</h3>
                        <p>admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel</p>
                        <p>admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel</p>
                        <p>admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- faq start -->
    <div class="container-fluid pb-5 border-top">
        <div class="container" id="faq">
            <div class="row">
                <div class="tab_text">
                    <h3>FAQs</h3>
                    <span>We've gathered some of the most asked questions about our aso affiliate program.</span>
                </div>
                <!-- Recent Terms & Conditions Section Start -->
                <section class="terms-section section-b-space section-bg">
                    <div class="container-fluid-lg">
                        <div class="terms-content">
                            <div class="row">
                                <div class="col-xxl-12 col-xl-12 col-lg-12 mx-auto">
                                    <div class="accordion" id="privacyPolicyExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#privacyPolicyCollapse__INDEX__one" aria-expanded="false"
                                                    aria-controls="privacyPolicyCollapse__INDEX__one">
                                                    1. Revisions to Our Terms &amp; Conditions
                                                    <i class="iconsax add" icon-name="add"></i>
                                                    <i class="iconsax minus" icon-name="minus"></i>
                                                </button>
                                            </h2>
                                            <div id="privacyPolicyCollapse__INDEX__one" class="accordion-collapse collapse show" data-bs-parent="#privacyPolicyExample">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>Our Privacy Policy might get updated periodically. If we do, we will inform you by updating the Privacy Policy on this page.</li>
                                                        <li>We will bring it to your knowledge via email or clear notice on our website before any changes to this Privacy Policy take effect. Remember to check this Privacy Policy from time to time for updates. Changes are effective when posted here.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#privacyPolicyCollapse__INDEX__two" aria-expanded="false"
                                                    aria-controls="privacyPolicyCollapse__INDEX__two">
                                                    1. Revisions to Our Terms &amp; Conditions
                                                    <i class="iconsax add" icon-name="add"></i>
                                                    <i class="iconsax minus" icon-name="minus"></i>
                                                </button>
                                            </h2>
                                            <div id="privacyPolicyCollapse__INDEX__two" class="accordion-collapse collapse" data-bs-parent="#privacyPolicyExample">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>Our Privacy Policy might get updated periodically. If we do, we will inform you by updating the Privacy Policy on this page.</li>
                                                        <li>We will bring it to your knowledge via email or clear notice on our website before any changes to this Privacy Policy take effect. Remember to check this Privacy Policy from time to time for updates. Changes are effective when posted here.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#privacyPolicyCollapse__INDEX__three" aria-expanded="false"
                                                    aria-controls="privacyPolicyCollapse__INDEX__three">
                                                    1. Revisions to Our Terms &amp; Conditions
                                                    <i class="iconsax add" icon-name="add"></i>
                                                    <i class="iconsax minus" icon-name="minus"></i>
                                                </button>
                                            </h2>
                                            <div id="privacyPolicyCollapse__INDEX__three" class="accordion-collapse collapse" data-bs-parent="#privacyPolicyExample">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>Our Privacy Policy might get updated periodically. If we do, we will inform you by updating the Privacy Policy on this page.</li>
                                                        <li>We will bring it to your knowledge via email or clear notice on our website before any changes to this Privacy Policy take effect. Remember to check this Privacy Policy from time to time for updates. Changes are effective when posted here.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#privacyPolicyCollapse__INDEX__four" aria-expanded="false"
                                                    aria-controls="privacyPolicyCollapse__INDEX__four">
                                                    1. Revisions to Our Terms &amp; Conditions
                                                    <i class="iconsax add" icon-name="add"></i>
                                                    <i class="iconsax minus" icon-name="minus"></i>
                                                </button>
                                            </h2>
                                            <div id="privacyPolicyCollapse__INDEX__four" class="accordion-collapse collapse" data-bs-parent="#privacyPolicyExample">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>Our Privacy Policy might get updated periodically. If we do, we will inform you by updating the Privacy Policy on this page.</li>
                                                        <li>We will bring it to your knowledge via email or clear notice on our website before any changes to this Privacy Policy take effect. Remember to check this Privacy Policy from time to time for updates. Changes are effective when posted here.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#privacyPolicyCollapse__INDEX__five" aria-expanded="false"
                                                    aria-controls="privacyPolicyCollapse__INDEX__five">
                                                    1. Revisions to Our Terms &amp; Conditions
                                                    <i class="iconsax add" icon-name="add"></i>
                                                    <i class="iconsax minus" icon-name="minus"></i>
                                                </button>
                                            </h2>
                                            <div id="privacyPolicyCollapse__INDEX__five" class="accordion-collapse collapse" data-bs-parent="#privacyPolicyExample">
                                                <div class="accordion-body">
                                                    <ul>
                                                        <li>Our Privacy Policy might get updated periodically. If we do, we will inform you by updating the Privacy Policy on this page.</li>
                                                        <li>We will bring it to your knowledge via email or clear notice on our website before any changes to this Privacy Policy take effect. Remember to check this Privacy Policy from time to time for updates. Changes are effective when posted here.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- income -->
    <div class="container pt-5 pb-5" id="income">
        <div class="income_tab_text">
            <h3>Income System</h3>
        </div>
        <div class="content-banner">
            <img src="{{ asset('frontend/images/410f99d22b802fb17f3cd457bdede19f.png') }}" alt="" class="img-fluid">
        </div>
        <div class="row pt-3">
            <div class="col-md-7">
                <div class="tab_text">
                    <h3>How to earn in aso Affiliate</h3>
                    <p>admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel admin will add this text from admin panel</p>
                </div>
            </div>
            <div class="col-md-5">
                <img src="{{ asset('frontend/images/ca7ed2fbe285a7f36b18055a88c029e5.png') }}" alt="" class="img-fluid">
            </div>
        </div>
    </div>

    <script>
        console.log('@json(__("frontend::static.categories"))');
    </script>
@endsection
