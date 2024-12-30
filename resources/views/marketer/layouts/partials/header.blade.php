@use('App\Models\Setting')
@use('App\Models\User')
@php
$notifications = [];
if (Auth::check()) {
    $user = User::findOrFail(Auth::user()->id);
    $notifications = $user->notifications()->latest('created_at');
}
$settings = Setting::first()->values;
@endphp
<!-- Page Header Start-->
<div class="page-main-header">
    <div class="main-header-right row">
        <form class="form-inline search-full col" action="#" method="get">
            <div class="form-group">
                <input type="text" class="form-control search-input  w-100" aria-describedby="searchHelp" placeholder="Search Here...">
                <i data-feather="search"></i>
                <i data-feather="x" class="close-search"></i>
            </div>
        </form>
        
        <div class="d-flex align-items-center w-auto gap-sm-3 gap-2 p-0">
            <div class="mobile-sidebar w-auto">
                <div class="media-body text-end switch-sm">
                    <label class="switch">
                        <span class="cursor-pointer">
                            <i data-feather="menu" id="sidebar-toggle"></i>
                        </span>
                    </label>
                </div>
            </div>
            @if ($themeOptions['header']['home'] ?? false)
            <a class="nav-link @if (Request::is('/')) active @endif"
                href="{{ url('/') }}">{{ __('frontend::static.header.home') }}</a>
            @endif
            <a href="{{ route('backend.dashboard') }}" class="d-lg-none d-flex mobile-logo">
                <img class="blur-up lazyloaded img-fluid dark-logo" src="{{ $settings['general']['light_logo'] ?? asset('admin/images/logo-dark.png') }}" alt="site-logo">
                <img class="blur-up lazyloaded img-fluid light-logo" src="{{ $settings['general']['light_logo'] ?? asset('admin/images/Logo-Light.png') }}" alt="site-logo">
            </a>
            <form class="search-form d-lg-flex d-none">
                <input type="text" class="form-control search-input" value="" id="menu-item-search" aria-describedby="searchHelp" placeholder="{{__('static.search_here')}}">
                <i data-feather="search"></i>
                <ul id="search-results" class="search-list custom-scroll d-none"></ul>
            </form>
        </div>
        <div class="nav-right col">
            <ul class="nav-menus">
                <li class="d-lg-none d-sm-inline-block d-none header-search" id="search-form">
                    <i data-feather="search" class="light-mode"></i>
                </li>

                <li class="onhover-dropdown">
                    <a class="txt-dark" href="javascript:void(0)">
                        <h6>{{ strtoupper(Session::get('locale', 'en')) }}</h6>
                    </a>
                    <ul class="language-dropdown onhover-show-div p-20  language-dropdown-hover">
                        @forelse (\App\Helpers\Helpers::getLanguages() as $lang)
                            <li>
                                <a href="{{ route('lang', @$lang?->locale) }}" data-lng="{{@$lang?->locale}}"><img class="active-icon" src="{{ @$lang?->flag ??  asset('admin/images/No-image-found.jpg')}}"><span>{{@$lang?->name}} ({{@$lang?->locale}})</span></a>
                            </li>
                        @empty
                            <li>
                                <a href="{{ route('lang', 'en') }}" data-lng="en"><img class="active-icon" src="{{ asset('admin/images/flags/LR.png') }}"><a href="javascript:void(0)" data-lng="en">English</a>
                            </li>
                        @endforelse
                    </ul>
                </li>
                <li class="dark-light-mode" id="dark-mode">
                    <i data-feather="moon" class="light-mode"></i>
                    <i data-feather="sun" class="dark-mode"></i>
                </li>
                <li class="onhover-dropdown">
                    <i data-feather="bell"></i>
                    <span class="badge badge-pill badge-primary pull-right notification-badge">{{count(auth()->user()->unreadNotifications)}}</span>
                    </span>
                    <ul class="notification-dropdown onhover-show-div">
                        <li>
                            <h4>{{ __('static.contact_mails') }}
                                <span class="badge badge-pill badge-primary pull-right">{{count(auth()->user()->unreadNotifications)}}</span>
                            </h4>
                        </li>
                        @forelse (auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                        <li>
                            <i data-feather="disc"></i>
                            <p>{{$notification->data['message'] ?? ''}}</p>
                        </li>
                        @empty
                        <div class="d-flex flex-column no-data-detail">
                            <img class="mx-auto d-flex" src="{{ asset('admin/images/svg/no-data.svg') }}" alt="no-image">
                            <div class="data-not-found">
                                <span>{{__('static.data_not_found')}}</span>
                            </div>
                        </div>
                        @endforelse
                        <li>
                            <a href="{{ route('backend.list-notification') }}" class="btn btn-primary">View All</a>
                        </li>
                    </ul>
                </li>
                <li class="onhover-dropdown">
                    <div class="media profile-box">
                        @if (Auth::user()->getFirstMediaUrl('image'))
                        <img class="align-self-center profile-image pull-right img-fluid rounded-circle blur-up lazyloaded" src="{{ Auth::user()->getFirstMediaUrl('image') }}" alt="header-user">
                        @else
                        <div class="initial-letter">{{ substr(Auth::user()->name, 0, 1) }}</div>
                        @endif
                        <span class="d-md-flex d-none">{{ Auth::user()->name }}</span>
                    </div>
                    <ul class="profile-dropdown onhover-show-div p-20 profile-dropdown-hover">
                        <li><a href="{{ route('backend.account.profile') }}">
                                <i data-feather="user"></i><span>{{ __('static.edit_profile') }}</span></a></li>
                        <li>
                            <a href="{{ route('frontend.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i data-feather="log-out"></i><span>{{ __('static.logout') }}</span>
                            </a>
                            <form action="{{route('frontend.logout')}}" method="POST" class="d-none" id="logout-form">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Page Header Ends -->

@push('js')
<script>
    function menuItemSearch() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById('menu-item-search');
        filter = input.value.toUpperCase();
        ul = document.getElementById("sidebar-menu");
        li = ul.getElementsByTagName('li');
        var resultsContainer = document.getElementById("search-results");

        if (filter !== '') {
            $("#search-results").removeClass("d-none").addClass("d-flex no-icons");
            resultsContainer.innerHTML = '';
            var hasMatches = false;

            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                if (a) {
                    txtValue = a.textContent || a.innerText;
                    if (a.getAttribute('href') !== "javascript:void(0);" && txtValue.toUpperCase().indexOf(filter) > -1) {
                        var clone = li[i].cloneNode(true);
                        resultsContainer.appendChild(clone);
                        hasMatches = true;
                    }
                }
            }

            if (!hasMatches) {
                resultsContainer.innerHTML = '<li class="no-data">No matches found</li>';
            }
        } else {
            $("#search-results").removeClass("d-flex no-icons").addClass("d-none");
        }
    }

    (function($) {
        "use strict";
        document.getElementById('menu-item-search').addEventListener('keyup', menuItemSearch);
    })(jQuery);
</script>
@endpush
