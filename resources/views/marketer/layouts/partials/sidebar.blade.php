
@use('App\Helpers\Helpers')
@use('App\Models\Setting')
@use('App\Enums\BookingEnumSlug')
@use('App\Enums\RoleEnum')
@php
    $settings = Setting::first()->values;
@endphp
<!-- Page Sidebar Start-->
<div class="page-sidebar">
    <div class="main-header-left">
        <div class="logo-wrapper">
            <a href="{{ route('backend.dashboard') }}">
                <img class="blur-up lazyloaded img-fluid"
                    src="{{ $settings['general']['light_logo'] ?? asset('admin/images/Logo-Light.png') }}"
                    alt="site-logo">
            </a>
            <i data-feather="menu" class="close-sidebar" id="close-sidebar"></i>
        </div>
    </div>
    <div class="sidebar custom-scrollbar">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="sidebar-main-title">
                <div>
                    <h6>{{ __('static.marketer.menu_title') }}</h6>
                </div>
            </li>
            <li>
                <a href="{{ route('affiliate.dashboard') }}"
                    class="sidebar-header {{ Request::is('affiliate/dashboard*') ? 'active' : '' }}">
                    <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/home-line.svg') }}">
                    <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/home-fill.svg') }}">
                    <span>{{ __('static.marketer.dashboard') }}</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);"
                    class="sidebar-header {{ Request::is('affiliate/generate_affiliate_link') || Request::is('affiliate/campaigns') || Request::is('affiliate/service_affiliate_links') || Request::is('affiliate/provider_affiliate_links') || Request::is('affiliate/banner_creatives') || Request::is('affiliate/marketing_resources') || Request::is('affiliate/marketing_guidelines') ? 'active' : '' }}">
                    <!-- <i data-feather="users"></i> -->
                    <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-line.svg') }}">
                    <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-fill.svg') }}">
                    <span>{{ __('static.marketer.marketing_tool') }}</span>
                    <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                </a>
                <ul class="sidebar-submenu {{ Request::is('affiliate/generate_affiliate_link') || Request::is('affiliate/campaigns') || Request::is('affiliate/service_affiliate_links') || Request::is('affiliate/provider_affiliate_links') || Request::is('affiliate/banner_creatives') || Request::is('affiliate/marketing_resources') || Request::is('affiliate/marketing_guidelines') ? 'menu-open' : '' }}">
                    <li>
                        <a href="{{route('affiliate.generate_affiliate_link')}}"
                            class="{{ Request::is('affiliate/generate_affiliate_link') ? 'active' : '' }}">{{ __('static.marketer.generate_affiliate_link') }}</a>
                    </li>
                    <li>
                        <a href="{{route('affiliate.campaigns')}}"
                            class="{{ Request::is('affiliate/campaigns') ? 'active' : '' }}">{{ __('static.marketer.campaigns') }}</a>
                    </li>
                    <li>
                        <a href="{{route('affiliate.service_affiliate_links')}}"
                            class="{{ Request::is('affiliate/service_affiliate_links') ? 'active' : '' }}">{{ __('static.marketer.service_affiliate_links') }}</a>
                    </li>
                    <li>
                        <a href="{{route('affiliate.provider_affiliate_links')}}"
                            class="{{ Request::is('affiliate/provider_affiliate_links') ? 'active' : '' }}">{{ __('static.marketer.provider_affiliate_links') }}</a>
                    </li>
                    <li>
                        <a href="{{route('affiliate.banner_creatives')}}"
                            class="{{ Request::is('affiliate/banner_creatives') ? 'active' : '' }}">{{ __('static.marketer.banner_creatives') }}</a>
                    </li>
                    <li>
                        <a href="{{route('affiliate.marketing_resources')}}"
                            class="{{ Request::is('affiliate/marketing_resources') ? 'active' : '' }}">{{ __('static.marketer.marketing_resources') }}</a>
                    </li>
                    <li>
                        <a href="{{route('affiliate.marketing_guidelines')}}"
                            class="{{ Request::is('affiliate/marketing_guidelines') ? 'active' : '' }}">{{ __('static.marketer.marketing_guidelines') }}</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);"
                    class="sidebar-header {{ Request::is('affiliate/customer_affiliate') || Request::is('affiliate/provder_affiliate') || Request::is('affiliate/downline_marketer')  ? 'active' : '' }}">
                    <!-- <i data-feather="users"></i> -->
                    <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-line.svg') }}">
                    <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-fill.svg') }}">
                    <span>{{ __('static.marketer.affiliate_teams') }}</span>
                    <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                </a>
                <ul class="sidebar-submenu {{ Request::is('affiliate/customer_affiliate') || Request::is('affiliate/provider_affiliate') || Request::is('affiliate/downline_marketer')  ? 'menu-open' : '' }}">
                    <li>
                        <a href="{{route('affiliate.customer_affiliate')}}"
                            class="{{ Request::is('affiliate/customer_affiliate') ? 'active' : '' }}">{{ __('static.marketer.customer_affiliate') }}</a>
                    </li>
                    <li>
                        <a href="{{route('affiliate.provider_affiliate')}}"
                            class="{{ Request::is('affiliate/provider_affiliate') ? 'active' : '' }}">{{ __('static.marketer.provider_affiliate') }}</a>
                    </li>
                    <li>
                        <a href="{{route('affiliate.downline_marketer')}}"
                            class="{{ Request::is('affiliate/downline_marketer') ? 'active' : '' }}">{{ __('static.marketer.downline_marketer') }}</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);"
                    class="sidebar-header {{ Request::is('affiliate/order_comm_reports') || Request::is('affiliate/campaign_reports') || Request::is('affiliate/referrals_history') ? 'active' : '' }}">
                    <!-- <i data-feather="users"></i> -->
                    <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-line.svg') }}">
                    <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-fill.svg') }}">
                    <span>{{ __('static.marketer.reports') }}</span>
                    <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                </a>
                <ul class="sidebar-submenu {{ Request::is('affiliate/order_comm_reports') || Request::is('affiliate/campaign_reports') || Request::is('affiliate/referrals_history') ? 'menu-open' : '' }}">
                    <li>
                        <a href="{{route('affiliate.order_comm_reports')}}"
                            class="{{ Request::is('affiliate/order_comm_reports')  ? 'active' : '' }}">{{ __('static.marketer.order_comm_reports') }}</a>
                    </li>
                    <li>
                        <a href="{{route('affiliate.campaign_reports')}}"
                            class="{{ Request::is('affiliate/campaign_reports') && !Request::is('affiliate') ? 'active' : '' }}">{{ __('static.marketer.campaign_reports') }}</a>
                    </li>
                    <li>
                        <a href="{{route('affiliate.referrals_history')}}"
                            class="{{ Request::is('affiliate/referrals_history') ? 'active' : '' }}">{{ __('static.marketer.referrals_history') }}</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);"
                    class="sidebar-header {{ Request::is('affiliate/*') || Request::is('affiliate/*') ? 'active' : '' }}">
                    <!-- <i data-feather="users"></i> -->
                    <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-line.svg') }}">
                    <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-fill.svg') }}">
                    <span>{{ __('static.marketer.earning_payment') }}</span>
                    <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                </a>
                <ul class="sidebar-submenu {{ Request::is('affiliate/user*') || Request::is('affiliate/role*') ? 'menu-open' : '' }}">
                    <li>
                        <a href="#"
                            class="{{ Request::is('affiliate') && !Request::is('affiliate') ? 'active' : '' }}">{{ __('static.marketer.earnings') }}</a>
                    </li>
                    <li>
                        <a href="#"
                            class="{{ Request::is('affiliate') && !Request::is('affiliate') ? 'active' : '' }}">{{ __('static.marketer.payments') }}</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);"
                    class="sidebar-header {{ Request::is('affiliate/*') || Request::is('affiliate/*') ? 'active' : '' }}">
                    <!-- <i data-feather="users"></i> -->
                    <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-line.svg') }}">
                    <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-fill.svg') }}">
                    <span>{{ __('static.marketer.pages') }}</span>
                    <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                </a>
                <ul class="sidebar-submenu {{ Request::is('affiliate/user*') || Request::is('affiliate/role*') ? 'menu-open' : '' }}">
                    <li>
                        <a href="#"
                            class="{{ Request::is('affiliate') && !Request::is('affiliate') ? 'active' : '' }}">{{ __('static.marketer.comission_rate') }}</a>
                    </li>
                    <li>
                        <a href="#"
                            class="{{ Request::is('affiliate') && !Request::is('affiliate') ? 'active' : '' }}">{{ __('static.marketer.affiliate_faq') }}</a>
                    </li>
                    <li>
                        <a href="#"
                            class="{{ Request::is('affiliate') && !Request::is('affiliate') ? 'active' : '' }}">{{ __('static.marketer.affiliate_agreement') }}</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);"
                    class="sidebar-header {{ Request::is('affiliate/*') || Request::is('affiliate/*') ? 'active' : '' }}">
                    <!-- <i data-feather="users"></i> -->
                    <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-line.svg') }}">
                    <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-fill.svg') }}">
                    <span>{{ __('static.marketer.help_support') }}</span>
                    <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                </a>
                <ul class="sidebar-submenu {{ Request::is('affiliate/user*') || Request::is('affiliate/role*') ? 'menu-open' : '' }}">
                    <li>
                        <a href="#"
                            class="{{ Request::is('affiliate') && !Request::is('affiliate') ? 'active' : '' }}">{{ __('static.marketer.vedio_tutorial') }}</a>
                    </li>
                    <li>
                        <a href="#"
                            class="{{ Request::is('affiliate') && !Request::is('affiliate') ? 'active' : '' }}">{{ __('static.marketer.support_faq') }}</a>
                    </li>
                    <li>
                        <a href="#"
                            class="{{ Request::is('affiliate') && !Request::is('affiliate') ? 'active' : '' }}">{{ __('static.marketer.contact') }}</a>
                    </li>
                    <li>
                        <a href="#"
                            class="{{ Request::is('affiliate') && !Request::is('affiliate') ? 'active' : '' }}">{{ __('static.marketer.live_chat') }}</a>
                    </li>
                    <li>
                        <a href="#"
                            class="{{ Request::is('affiliate') && !Request::is('affiliate') ? 'active' : '' }}">{{ __('static.marketer.support_ticket') }}</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="#"
                    class="sidebar-header {{ Request::is('affiliate/dashboard*') ? 'active' : '' }}">
                    <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-line.svg') }}">
                    <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/setting-fill.svg') }}">
                    <span>{{ __('static.marketer.setting') }}</span>
                </a>
            </li>
           
        </ul>
    </div>
</div>
<!-- Page Sidebar End -->
