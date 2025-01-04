
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
                    <h6>{{ __('static.dashboard.dashboard') }}</h6>
                </div>
            </li>
            <li>
                <a href="{{ route('backend.dashboard') }}"
                    class="sidebar-header {{ Request::is('backend/dashboard*') ? 'active' : '' }}">
                    <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/home-line.svg') }}">
                    <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/home-fill.svg') }}">
                    <span>{{ __('static.dashboard.dashboard') }}</span>
                </a>
            </li>
            @canAny(['backend.user.index', 'backend.serviceman_withdraw_request.index', 'backend.role.index',
                'backend.serviceman.index', 'backend.serviceman_wallet.index', 'backend.provider.index',
                'backend.provider_document.index', 'backend.provider_time_slot.index', 'backend.provider_wallet.index',
                'backend.withdraw_request.index'])
                <li class="sidebar-main-title">
                    <div>
                        <h6>{{ __('static.dashboard.user_management') }}</h6>
                    </div>
                </li>
                @canAny(['backend.user.index', 'backend.role.index'])
                    <li>
                        <a href="javascript:void(0);"
                            class="sidebar-header {{ Request::is('backend/user*') || Request::is('backend/role*') ? 'active' : '' }}">
                            <!-- <i data-feather="users"></i> -->
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/users-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/users-fill.svg') }}">
                            <span>{{ __('static.users.system_users') }}</span>
                            <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                        </a>
                        <ul class="sidebar-submenu {{ Request::is('backend/user*') || Request::is('backend/role*') ? 'menu-open' : '' }}">
                            @can('backend.user.index')
                                <li>
                                    <a href="{{ route('backend.user.index') }}"
                                        class="{{ Request::is('backend/user') && !Request::is('backend/user/create') ? 'active' : '' }}">{{ __('static.users.all') }}</a>
                                </li>
                            @endcan
                            @can('backend.users.create')
                                <li>
                                    <a href="{{ route('backend.user.create') }}"
                                        class="{{ Request::is('backend/user/create') ? 'active' : '' }}">{{ __('static.users.create') }}</a>
                                </li>
                            @endcan
                            @can('backend.role.index')
                                <li>
                                    <a href="{{ route('backend.role.index') }}"
                                        class="{{ Request::is('backend/role*') || Request::is('backend/role/create') ? 'active' : '' }}">{{ __('static.role') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanAny
                @canAny(['backend.customer.index', 'backend.wallet.index'])
                    <li>
                        <a href="javascript:void(0);"
                            class="sidebar-header {{ Request::is('backend/customer*') || Request::is('backend/wallet*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/users-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/users-fill.svg') }}">
                            <span>{{ __('static.customer.customers') }}</span>
                            <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                        </a>
                        <ul class="sidebar-submenu {{ Request::is('backend/customer*') || Request::is('backend/wallet*') ? 'menu-open' : '' }}">
                            @can('backend.customer.index')
                                <li>
                                    <a href="{{ route('backend.customer.index') }}"
                                        class="{{ Request::is('backend/customer') && !Request::is('backend/customer/create') ? 'active' : '' }}">{{ __('static.customer.all') }}</a>
                                </li>
                            @endcan
                            @can('backend.customer.create')
                                <li>
                                    <a href="{{ route('backend.customer.create') }}"
                                        class="{{ Request::is('backend/customer/create') ? 'active' : '' }}">{{ __('static.customer.create') }}</a>
                                </li>
                            @endcan
                            @can('backend.wallet.index')
                                <li>
                                    <a href="{{ route('backend.wallet.index') }}"
                                        class="{{ Request::is('backend/wallet*') ? 'active' : '' }}">{{ __('static.wallet.wallet') }}</a>
                                </li>
                            @endcan
                            @can('backend.unverified_user.index')
                                <li>
                                    <a href="{{ route('backend.unverfied-users.index' , ['role' => RoleEnum::CONSUMER]) }}"
                                        class="{{ Request::is('backend/unverfied-users*') ? 'active' : '' }}">{{ __('static.unverfied_users.unverfied_consumer') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanAny

                @canAny(['backend.commission_history.index', 'backend.provider.index', 'backend.provider_document.index',
                    'backend.provider_time_slot.index', 'backend.provider_wallet.index', 'backend.withdraw_request.index'])
                    <li>
                        <a href="javascript:void(0);"
                            class="sidebar-header {{ (!Request::is('backend/providerSiteService*') && Request::is('backend/provider*')) || Request::is('backend/commission*') || Request::is('backend/withdraw-request*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/users-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/users-fill.svg') }}">
                            <span>{{ __('static.provider.providers') }}</span>
                            <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                        </a>
                        <ul class="sidebar-submenu {{ (!Request::is('backend/providerSiteService*') && Request::is('backend/provider*')) || Request::is('backend/withdraw-request*') || Request::is('backend/commission*') ? 'menu-open' : '' }}">
                            @can('backend.provider.index')
                                <li>
                                    <a href="{{ route('backend.provider.index') }}"
                                        class="{{ !Request::is('backend/providerSiteService*') && Request::is('backend/provider') && !Request::is('backend/provider/create') && !Request::is('backend/provider-document*') ? 'active' : '' }}">{{ __('static.provider.all') }}</a>
                                </li>
                            @endcan
                            @can('backend.provider.create')
                                <li>
                                    <a href="{{ route('backend.provider.create') }}"
                                        class="{{ Request::is('backend/provider/create') ? 'active' : '' }}">{{ __('static.provider.create') }}</a>
                                </li>
                            @endcan
                            @can('backend.provider_wallet.index')
                                <li>
                                    <a href="{{ route('backend.provider-wallet.index') }}"
                                        class="{{ Request::is('backend/provider-wallet*') ? 'active' : '' }}">{{ __('static.wallet.wallet') }}</a>
                                </li>
                            @endcan
                            @can('backend.provider_document.index')
                                <li>
                                    <a href="{{ route('backend.provider-document.index') }}"
                                        class="{{ Request::is('backend/provider-document*') ? 'active' : '' }}">{{ __('static.provider_document.provider_documents') }}</a>
                                </li>
                            @endcan
                            @can('backend.provider_time_slot.index')
                                <li>
                                    <a href="{{ route('backend.provider-time-slot.index') }}"
                                        class="{{ Request::is('backend/provider-time-slot*') ? 'active' : '' }}">{{ __('static.provider_time_slot.provider_time_slot') }}</a>
                                </li>
                            @endcan
                            @can('backend.commission_history.index')
                                <li>
                                    <a href="{{ route('backend.commission.index') }}"
                                        class="{{ Request::is('backend/commission*') ? 'active' : '' }}">{{ __('static.commission_history.commission_history') }}</a>
                                </li>
                            @endcan
                            @can('backend.withdraw_request.index')
                                <li>
                                    <a href="{{ route('backend.withdraw-request.index') }}"
                                        class="{{ Request::is('backend/withdraw-request*') ? 'active' : '' }}">{{ __('static.withdraw.withdraw_request') }}</a>
                                </li>
                            @endcan
                            @can('backend.unverified_user.index')
                                <li>
                                    <a href="{{ route('backend.unverfied-users.index' , ['role' => RoleEnum::PROVIDER]) }}"
                                        class="{{ Request::is('backend/unverified-users*') ? 'active' : '' }}">{{ __('static.unverfied_users.unverfied_provider') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanAny

                @canAny(['backend.serviceman.index', 'backend.serviceman.create',
                    'backend.serviceman_withdraw_request.index', 'backend.serviceman_wallet.index'])
                    <li>
                        <a href="javascript:void(0);"
                            class="sidebar-header {{ Request::is('backend/serviceman*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/users-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/users-fill.svg') }}">
                            <span>{{ __('static.serviceman.servicemen') }}</span>
                            <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                        </a>
                        <ul class="sidebar-submenu {{ Request::is('backend/serviceman*') ? 'menu-open' : '' }}">
                            @can('backend.serviceman.index')
                                <li>
                                    <a href="{{ route('backend.serviceman.index') }}"
                                        class=" {{ Request::is('backend/serviceman') ? 'active' : '' }}">{{ __('static.serviceman.all') }}</a>
                                </li>
                            @endcan
                            @can('backend.serviceman.create')
                                <li>
                                    <a href="{{ route('backend.serviceman.create') }}"
                                        class=" {{ Request::is('backend/serviceman/create') ? 'active' : '' }}">{{ __('static.serviceman.create') }}</a>
                                </li>
                            @endcan
                            @can('backend.serviceman_wallet.index')
                                <li>
                                    <a href="{{ route('backend.serviceman-wallet.index') }}"
                                        class="{{ Request::is('backend/serviceman-wallet*') ? 'active' : '' }}">{{ __('static.wallet.wallet') }}</a>
                                </li>
                            @endcan
                            @can('backend.serviceman_withdraw_request.index')
                                <li>
                                    <a href="{{ route('backend.serviceman-withdraw-request.index') }}"
                                        class="{{ Request::is('backend/serviceman-withdraw-request*') ? 'active' : '' }}">{{ __('static.withdraw.withdraw_request') }}</a>
                                </li>
                            @endcan
                            @can('backend.serviceman_location.index')
                                <li>
                                    <a href="{{ route('backend.serviceman-location.index') }}"
                                        class="{{ Request::is('backend/serviceman-location*') ? 'active' : '' }}">{{ __('static.serviceman.locations') }}</a>
                                </li>
                            @endcan

                            @can('backend.unverified_user.index')
                                <li>
                                    <a href="{{ route('backend.unverfied-users.index' , ['role' => RoleEnum::SERVICEMAN]) }}"
                                        class="{{ Request::is('backend/unverfied-users*') ? 'active' : '' }}">{{ __('static.unverfied_users.unverfied_serviceman') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanAny
            @endcan
            @can('backend.unverified_user.index')
                <li>
                    <a href="{{ route('backend.unverfied-users.index') }}"
                        class="sidebar-header {{ Request::is('backend/unverfied-users*') ? 'active' : '' }}">
                        <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/unverified-users-line.svg') }}">
                        <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/unverified-users-fill.svg') }}">
                        <span>{{ __('static.unverfied_users.unverfied_users') }}</span>
                    </a>
                </li>
            @endcan

            @canAny(['backend.zone.index', 'backend.service-package.index', 'backend.service.index',
                'backend.service_category.index', 'backend.service_request.index'])
                <li class="sidebar-main-title">
                    <div>
                        <h6>{{ __('static.dashboard.service_management') }}</h6>
                    </div>
                </li>
                @can('backend.zone.index')
                    <li>
                        <a href="{{ route('backend.zone.index') }}"
                            class="sidebar-header {{ Request::is('backend/zone*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/blogs-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/blogs-fill.svg') }}">
                            <span>{{ __('static.zone.zones') }}</span>
                        </a>
                    </li>
                @endcan

                @canAny(['backend.service-package.index', 'backend.service.index', 'backend.service_category.index'])
                    <li>
                        <a href="javascript:void(0);"
                            class="sidebar-header {{ (Request::is('backend/service*') && !Request::is('backend/serviceman*') && !Request::is('backend/servicemen-review*') && !Request::is('backend/service-requests*')) || Request::is('backend/service-package*') || Request::is('backend/category*') || Request::is('backend/providerSiteService*') || Request::is('backend/additional-service*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/service-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/service-fill.svg') }}">
                            <span>{{ __('static.service.services') }}</span>
                            <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                        </a>
                        <ul
                            class="sidebar-submenu {{ (Request::is('backend/service*') && !Request::is('backend/serviceman*') && !Request::is('backend/servicemen-review*') && !Request::is('backend/service-requests*')) || Request::is('backend/category*') || Request::is('backend/providerSiteService*') || Request::is('backend/additional-service*') ? 'menu-open' : '' }}">
                            @can('backend.service.index')
                                <li>
                                    <a href="{{ route('backend.service.index') }}"
                                        class="{{ Request::is('backend/service*') && !Request::is('backend/service/create') && !Request::is('backend/serviceman*') && !Request::is('backend/service-package*') && !Request::is('backend/servicemen-review*') && !Request::is('backend/service-requests*') ? 'active' : '' }}">{{ __('static.service.all') }}</a>
                                </li>
                            @endcan
                            @can('backend.service.create')
                                <li>
                                    <a href="{{ route('backend.service.create') }}"
                                        class="{{ Request::is('backend/service/create') ? 'active' : '' }}">{{ __('static.service.create') }}</a>
                                </li>
                            @endcan
                            @can('backend.service_category.index')
                                <li>
                                    <a href="{{ route('backend.category.index') }}"
                                        class="{{ Request::is('backend/category*') ? 'active' : '' }}">{{ __('static.categories.categories') }}</a>
                                </li>
                            @endcan
                            @if (isset($settings['activation']['additional_services']) && $settings['activation']['additional_services'])
                                @can('backend.service.index')
                                    <li>
                                        <a href="{{ route('backend.additional-service.index') }}"
                                            class="{{ Request::is('backend/additional-service*') ? 'active' : '' }}">{{ __('static.additional_service.additional_services') }}</a>
                                    </li>
                                @endcan
                            @endif
                            @can('backend.service-package.index')
                                <li>
                                    <a href="{{ route('backend.service-package.index') }}"
                                        class="{{ Request::is('backend/service-package*') ? 'active' : '' }}">{{ __('static.service_package.service_packages') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanAny
                @can('backend.service_request.index')
                    <li>
                        <a href="{{ route('backend.service-requests.index') }}"
                            class="sidebar-header {{ Request::is('backend/service-requests*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/global-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/global-fill.svg') }}">
                            <span>{{ __('static.service_request.service_requests') }}</span>
                        </a>
                    </li>
                @endcan
            @endcan

            @canAny(['backend.testimonial.index', 'backend.booking.index', 'backend.payment_method.index',
                'backend.review.index'])
                <li class="sidebar-main-title">
                    <div>
                        <h6>{{ __('static.dashboard.booking_management') }}</h6>
                    </div>
                </li>
                <li>
                    <a href="javascript:void(0);"
                        class="sidebar-header {{ Request::is('backend/booking*') ? 'active' : '' }}">
                        <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/calendar-line.svg') }}">
                        <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/calendar-fill.svg') }}">
                        <span>{{ __('static.booking.bookings') }}</span>
                        <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                    </a>

                    <ul class="sidebar-submenu {{ Request::is('backend/booking*') ? 'menu-open' : '' }}">
                        @can('backend.booking.index')
                            <li>
                                <a href="{{ route('backend.booking.index') }}"
                                    class="{{ Request::fullUrlIs(route('backend.booking.index')) ? 'active' : '' }}">
                                    {{ __('static.booking.all') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('backend.booking.index', ['status' => BookingEnumSlug::PENDING]) }}"
                                    class="{{ request('status') === BookingEnumSlug::PENDING ? 'active' : '' }}">
                                    {{ __('static.booking.pending') }}
                                </a>
                                <span class="badge">{{ Helpers::getBookingsCountByStatus(BookingEnumSlug::PENDING) }}</span>
                            </li>
                            <li>
                                <a href="{{ route('backend.booking.index', ['status' => BookingEnumSlug::ACCEPTED]) }}"
                                    class="{{ request('status') === BookingEnumSlug::ACCEPTED ? 'active' : '' }}">
                                    {{ __('static.booking.accepted') }}
                                </a>
                                <span class="badge">{{ Helpers::getBookingsCountByStatus(BookingEnumSlug::ACCEPTED) }}</span>
                            </li>
                            <li>
                                <a href="{{ route('backend.booking.index', ['status' => BookingEnumSlug::ASSIGNED]) }}"
                                    class="{{ request('status') === BookingEnumSlug::ASSIGNED ? 'active' : '' }}">
                                    {{ __('static.booking.assigned') }}
                                </a>
                                <span class="badge">{{ Helpers::getBookingsCountByStatus(BookingEnumSlug::ASSIGNED) }}</span>
                            </li>
                            <li>
                                <a href="{{ route('backend.booking.index', ['status' => BookingEnumSlug::ON_THE_WAY]) }}"
                                    class="{{ request('status') === BookingEnumSlug::ON_THE_WAY ? 'active' : '' }}">
                                    {{ __('static.booking.on_the_way') }}
                                </a>
                                <span class="badge">{{ Helpers::getBookingsCountByStatus(BookingEnumSlug::ON_THE_WAY) }}</span>
                            </li>
                            <li>
                                <a href="{{ route('backend.booking.index', ['status' => BookingEnumSlug::ON_GOING]) }}"
                                    class="{{ request('status') === BookingEnumSlug::ON_GOING ? 'active' : '' }}">
                                    {{ __('static.booking.on_going') }}
                                </a>
                                <span class="badge">{{ Helpers::getBookingsCountByStatus(BookingEnumSlug::ON_GOING) }}</span>
                            </li>
                            <li>
                                <a href="{{ route('backend.booking.index', ['status' => BookingEnumSlug::CANCEL]) }}"
                                    class="{{ request('status') === BookingEnumSlug::CANCEL ? 'active' : '' }}">
                                    {{ __('static.booking.cancel') }}
                                </a>
                                <span class="badge">{{ Helpers::getBookingsCountByStatus(BookingEnumSlug::CANCEL) }}</span>
                            </li>
                            <li>
                                <a href="{{ route('backend.booking.index', ['status' => BookingEnumSlug::COMPLETED]) }}"
                                    class="{{ request('status') === BookingEnumSlug::COMPLETED ? 'active' : '' }}">
                                    {{ __('static.booking.completed') }}
                                </a>
                                <span class="badge">{{ Helpers::getBookingsCountByStatus(BookingEnumSlug::COMPLETED) }}</span>
                            </li>
                        @endcan
                    </ul>
                </li>

                @can('backend.payment_method.index')
                    <li>
                        <a href="{{ route('backend.transaction.index') }}"
                            class="sidebar-header {{ Request::is('backend/transaction*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/transactions-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/transactions-fill.svg') }}">
                            <span>{{ __('static.transaction.transactions') }}</span>
                        </a>
                    </li>
                @endcan

                @can('backend.review.index')
                    <li>
                        <a href="javascript:void(0);"
                            class="sidebar-header {{ Request::is('backend/review*') || Request::is('backend/servicemen-review') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/review-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/review-fill.svg') }}">
                            <span>{{ __('static.review.all') }}</span>
                            <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                        </a>
                        <ul
                            class="sidebar-submenu {{ Request::is('backend/review*') || Request::is('backend/servicemen-review*') ? 'menu-open' : '' }}">
                            <li>
                                <a href="{{ route('backend.review.index') }}"
                                    class="{{ Request::is('backend/review*') ? 'active' : '' }}">{{ __('static.review.service_reviews') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('backend.servicemen-review') }}"
                                    class="{{ Request::is('backend/servicemen-review*') ? 'active' : '' }}">{{ __('static.review.serviceman_reviews') }}</a>
                            </li>
                        </ul>
                    </li>
                @endcan
            @endcan



            @canAny(['backend.coupon.index', 'backend.plan.index', 'backend.banner.index'])
                <li class="sidebar-main-title">
                    <div>
                        <h6>{{ __('static.dashboard.marketing_advertising') }}</h6>
                    </div>
                </li>
                @can('backend.coupon.index')
                    <li>
                        <a href="{{ route('backend.coupon.index') }}"
                            class="sidebar-header {{ Request::is('backend/coupon*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/coupon-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/coupon-fill.svg') }}">
                            <span>{{ __('static.coupon.coupons') }}</span>
                        </a>
                    </li>
                @endcan
                @if (Helpers::isModuleEnable('Subscription'))
                    @can('backend.plan.index')
                        <li>
                            <a href="javascript:void(0);"
                                class="sidebar-header  {{ Request::is('backend/plan*') || Request::is('backend/subscriptions*') ? 'active' : '' }}">
                                <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/transactions-line.svg') }}">
                                <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/transactions-fill.svg') }}">
                                <span>{{ __('static.plan.plans') }}</span>
                                <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                            </a>
                            <ul class="sidebar-submenu {{ Request::is('backend/plan*') || Request::is('backend/subscriptions*') ? 'menu-open' : '' }}">
                                <li>
                                    <a href="{{ route('backend.plan.index') }}"
                                        class="{{ Request::is('backend/plan*') ? 'active' : '' }}">{{ __('static.plan.all') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('backend.subscription.index') }}"
                                        class="{{ Request::is('backend/subscriptions*') ? 'active' : '' }}">{{ __('static.plan.subscriptions') }}</a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                @endif
                @can('backend.banner.index')
                    <li>
                        <a href="javascript:void(0);"
                            class="sidebar-header {{ Request::is('backend/banner*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/chart-2-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/chart-2-fill.svg') }}">
                            <span>{{ __('static.banner.banners') }}</span>
                            <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                        </a>
                        <ul class="sidebar-submenu {{ Request::is('backend/banner*') ? 'menu-open' : '' }}">
                            @can('backend.banner.index')
                                <li>
                                    <a href="{{ route('backend.banner.index') }}"
                                        class="{{ Request::is('backend/banner') && !Request::is('backend/banner/create') ? 'active' : '' }}">{{ __('static.banner.all') }}</a>
                                </li>
                            @endcan
                            @can('backend.banner.create')
                                <li>
                                    <a href="{{ route('backend.banner.create') }}"
                                        class="{{ Request::is('backend/banner/create') ? 'active' : '' }}">{{ __('static.banner.create') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('backend.push_notification.index')
                    <li>
                        <a href="javascript:void(0);"
                            class="sidebar-header {{ Request::is('backend/notifications*') || Request::is('backend/push-notifications') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/notification-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/notification-fill.svg') }}">
                            <span>{{ __('static.notification.notifications') }}</span>
                            <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                        </a>
                        <ul class="sidebar-submenu {{ Request::is('backend/notifications') || Request::is('backend/push-notifications') ? 'menu-open' : '' }}">
                            @can('backend.push_notification.index')
                                <li>
                                    <a href="{{ route('backend.notifications') }}"
                                        class="{{ Request::is('backend/notifications*') && !Request::is('backend/push-notifications') ? 'active' : '' }}">{{ __('static.notification.list_notifications') }}</a>
                                </li>
                            @endcan
                            @can('backend.push_notification.create')
                                <li>
                                    <a href="{{ route('backend.push-notifications') }}"
                                        class="{{ Request::is('backend/push-notifications') ? 'active' : '' }}">{{ __('static.notification.send') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('backend.news_letter.index')
                    <li>
                        <a href="{{ route('backend.subscribers') }}"
                            class="sidebar-header  {{ Request::is('backend/subscribers') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/service-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/service-fill.svg') }}">
                            <span>{{ __('static.subscribers.subscribers') }}</span>
                        </a>
                    </li>
                @endcan
                @can('backend.testimonial.index')
                    <li>
                        <a href="{{ route('backend.testimonial.index') }}"
                            class="sidebar-header  {{ Request::is('backend/testimonial') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/service-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/service-fill.svg') }}">
                            <span>{{ __('static.testimonials.testimonials') }}</span>
                        </a>
                    </li>
                @endcan
            @endcan

            @canAny(['backend.tax.index', 'backend.currency.index'])
                <li class="sidebar-main-title">
                    <div>
                        <h6>{{ __('static.dashboard.financial_management') }}</h6>
                    </div>
                </li>
                @can('backend.tax.index')
                    <li>
                        <a href="{{ route('backend.tax.index') }}"
                            class="sidebar-header {{ Request::is('backend/tax*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/percentage-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/percentage-fill.svg') }}">
                            <span>{{ __('static.tax.taxes') }}</span>
                        </a>
                    </li>
                @endcan
                @can('backend.currency.index')
                    <li>
                        <a href="{{ route('backend.currency.index') }}" class="sidebar-header {{ Request::is('backend/currency*') ? 'active' : '' }}">
                        <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/dollar-line.svg') }}">
                        <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/dollar-fill.svg') }}">
                        <span>{{ __('static.currency.currencies') }}</span>
                        </a>
                    </li>
                @endcan
            @endcan

            @canAny(['backend.blog_category.index', 'backend.tag.index', 'backend.blog.index', 'backend.page.index'])
                <li class="sidebar-main-title">
                    <div>
                        <h6>{{ __('static.dashboard.content_management') }}</h6>
                    </div>
                </li>
                @if (isset($settings['activation']['blogs_enable']) && $settings['activation']['blogs_enable'])
                    @canAny(['backend.blog_category.index', 'backend.tag.index', 'backend.blog.index'])
                        <li>
                            <a href="javascript:void(0);"
                                class="sidebar-header {{ Request::is('backend/tag*') || Request::is('backend/blog*') || Request::is('backend/blog-category*') ? 'active' : '' }}">
                                <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/coupon-line.svg') }}">
                                <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/coupon-fill.svg') }}">
                                <span>{{ __('static.blog.blogs') }}</span>
                                <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                            </a>
                            <ul
                                class="sidebar-submenu {{ Request::is('backend/blog*') || Request::is('backend/tag*') ? 'menu-open' : '' }}">
                                @can('backend.blog.index')
                                    <li>
                                        <a href="{{ route('backend.blog.index') }}"
                                            class="{{ Request::is('backend/blog') ? 'active' : '' }}">{{ __('static.blog.all') }}</a>
                                    </li>
                                @endcan
                                @can('backend.blog.create')
                                    <li>
                                        <a href="{{ route('backend.blog.create') }}"
                                            class="{{ Request::is('backend/blog/create') ? 'active' : '' }}">{{ __('static.blog.create') }}</a>
                                    </li>
                                @endcan
                                @can('backend.blog_category.index')
                                    <li>
                                        <a href="{{ route('backend.blog-category.index') }}"
                                            class="{{ Request::is('backend/blog-category*') ? 'active' : '' }}">{{ __('static.categories.categories') }}</a>
                                    </li>
                                @endcan
                                @can('backend.tag.index')
                                    <li>
                                        <a href="{{ route('backend.tag.index') }}"
                                            class="{{ Request::is('backend/tag*') ? 'active' : '' }}">{{ __('static.tag.tags') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanAny

                    <!-- Faq Category Management -->
                    {{-- @canAny(['backend.faq-category.index', 'backend.faq.index']) --}}
                        <li>
                            <a href="javascript:void(0);"
                                class="sidebar-header {{ Request::is('backend/tag*') || Request::is('backend/faq_category*') || Request::is('backend/faq-category*') ? 'active' : '' }}">
                                <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/coupon-line.svg') }}">
                                <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/coupon-fill.svg') }}">
                                <span>{{ 'Faqs' }}</span>
                                <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                            </a>
                            <ul
                                class="sidebar-submenu {{ Request::is('backend/faq_category*') || Request::is('backend/faq-category*') ? 'menu-open' : '' }}">
                                {{-- @can('backend.faq-category.index') --}}
                                    <li>
                                        <a href="{{ route('backend.faq-category.index') }}" class="{{ Request::is('backend/faq-category') ? 'active' : '' }}">
                                            {{ 'All Faq Categories' }}
                                        </a>
                                    </li>
                                {{-- @endcan --}}
                                {{-- @can('backend.faq-category.create') --}}
                                    <li>
                                        <a href="{{ route('backend.faq-category.create') }}" class="{{ Request::is('backend/faq-category/create') ? 'active' : '' }}">
                                            {{ 'Add Faq Category' }}
                                        </a>
                                    </li>
                                {{-- @endcan --}}
                                {{-- @can('backend.faq.index') --}}
                                    <li>
                                        <a href="{{ route('backend.faq.index') }}" class="{{ Request::is('backend/faq*') ? 'active' : '' }}">
                                            {{ 'All Faqs' }}
                                        </a>
                                    </li>
                                {{-- @endcan --}}
                                {{-- @can('backend.faq.index') --}}
                                    <li>
                                        <a href="{{ route('backend.faq.index') }}" class="{{ Request::is('backend/faq*') ? 'active' : '' }}">
                                            {{ 'Add Faq' }}
                                        </a>
                                    </li>
                                {{-- @endcan --}}
                            </ul>
                        </li>
                    {{-- @endcanAny --}}
                @endif
                @can('backend.page.index')
                    <li>
                        <a href="{{ route('backend.page.index') }}"
                            class="sidebar-header {{ Request::is('backend/page*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/pages-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/pages-fill.svg') }}">
                            <span>{{ __('static.page.pages') }}</span>
                        </a>
                    </li>
                @endcan
            @endcan

            @canAny(['backend.document.index', 'backend.language.index', 'backend.payment_method.index',
                'backend.setting.index'])
                <li class="sidebar-main-title">
                    <div>
                        <h6>{{ __('static.dashboard.settings_management') }}</h6>
                    </div>
                </li>
                @can('backend.document.index')
                    <li>
                        <a href="javascript:void(0);" class="sidebar-header {{ Request::is('backend/document*') ? 'active' : '' }}">
                        <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/document-line.svg') }}">
                        <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/document-fill.svg') }}">
                        <span>{{ __('static.document.document') }}</span>
                        <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                        </a>
                        <ul class="sidebar-submenu {{ Request::is('backend/document*') ? 'menu-open' : '' }}">
                            @can('backend.document.index')
                                <li>
                                    <a href="{{ route('backend.document.index') }}"
                                        class="{{ Request::is('backend/document*') && !Request::is('backend/document/create') ? 'active' : '' }}">{{ __('static.document.all') }}</a>
                                </li>
                            @endcan
                            @can('backend.document.create')
                                <li>
                                    <a href="{{ route('backend.document.create') }}"
                                        class="{{ Request::is('backend/document/create') ? 'active' : '' }}">{{ __('static.document.create') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @canAny(['backend.theme_option.index', 'backend.home_page.index'])
                    <li>
                        <a href="javascript:void(0);"
                            class="sidebar-header {{ Request::is('backend/home-page*') || Request::is('backend/theme-option*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/file-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/file-fill.svg') }}">
                            <span>{{ __('static.appearances.appearances') }}</span>
                            <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                        </a>
                        <ul
                            class="sidebar-submenu {{ Request::is('backend/home-page*') || Request::is('backend/theme-options*') ? 'menu-open' : '' }}">
                            @can('backend.theme_option.index')
                                <li>
                                    <a href="{{ route('backend.theme_options.index') }}"
                                        class="{{ Request::is('backend/theme-options*') ? 'active' : '' }}">{{ __('static.theme_options.theme_options') }}</a>
                                </li>
                            @endcan
                            @can('backend.home_page.index')
                                <li>
                                    <a href="{{ route('backend.home_page.index') }}"
                                        class="{{ Request::is('backend/home-page*') ? 'active' : '' }}">{{ __('static.appearances.home_page') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanAny
                @can('backend.language.index')
                    <li>
                        <a href="{{ route('backend.systemLang.index') }}"
                            class="sidebar-header {{ Request::is('backend/systemLang*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/language-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/language-fill.svg') }}">
                            <span>{{ __('static.language.languages') }}</span>
                        </a>
                    </li>
                @endcan
                @canAny(['backend.email_template.index', 'backend.sms_template.index',
                    'backend.push_notification_template.index'])
                    <li>
                        <a href="javascript:void(0);"
                            class="sidebar-header {{ Request::is('backend/tag*') || Request::is('backend/blog*') || Request::is('backend/blog-category*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/edit-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/edit-fill.svg') }}">
                            <span>{{ __('static.notify_templates.notify_templates') }}</span>
                            <img class="stroke-icon" src="{{ asset('admin/images/svg/arrow-right-2.svg') }}">
                        </a>
                        <ul
                            class="sidebar-submenu {{ Request::is('backend/email-template') || Request::is('backend/sms-template') || Request::is('backend/push-notification-template') ? 'menu-open' : '' }}">
                            @can('backend.email_template.index')
                                <li>
                                    <a href="{{ route('backend.email-template.index') }}"
                                        class="{{ Request::is('backend/email-template') ? 'active' : '' }}">{{ __('static.email_templates.email') }}</a>
                                </li>
                            @endcan
                            @can('backend.sms_template.index')
                                <li>
                                    <a href="{{ route('backend.sms-template.index') }}"
                                        class="{{ Request::is('backend/sms-template') ? 'active' : '' }}">{{ __('static.sms_templates.sms') }}</a>
                                </li>
                            @endcan
                            @can('backend.push_notification_template.index')
                                <li>
                                    <a href="{{ route('backend.push-notification-template.index') }}"
                                        class="{{ Request::is('backend/push-notification-template') ? 'active' : '' }}">{{ __('static.push_notification_templates.push_notification') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('backend.payment_method.index')
                    <li>
                        <a href="{{ route('backend.paymentmethods.index') }}"
                            class="sidebar-header {{ Request::is('backend/payment-methods*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/payment-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/payment-fill.svg') }}">
                            <span>{{ __('static.payment_methods.payment_methods') }}</span>
                        </a>
                    </li>
                @endcan
                @can('backend.sms_gateway.index')
                    <li>
                        <a href="{{ route('backend.smsgateways.index') }}"
                            class="sidebar-header {{ Request::is('backend/sms-gateways*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/sms-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/sms-fill.svg') }}">
                            <span>{{ __('static.sms_gateways.sms_gateways') }}</span>
                        </a>
                    </li>
                @endcan
                @can('backend.custom_sms_gateway.index')
                    <li>
                        <a href="{{ route('backend.custom-sms-gateway.index') }}"
                            class="sidebar-header {{ Request::is('backend/custom-sms-gateway*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/sms-gateways-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/sms-gateways-fill.svg') }}">
                            <span>{{ __('static.custom_sms_gateways.custom_sms_gateways') }}</span>
                        </a>
                    </li>
                @endcan
                @can('backend.setting.index')
                    <li>
                        <a href="{{ route('backend.settings.index') }}"
                            class="sidebar-header {{ Request::is('backend/settings*') ? 'active' : '' }}">
                            <img class="inactive-icon" src="{{ asset('admin/images/svg/sidebar-icon/service-line.svg') }}">
                            <img class="active-icon" src="{{ asset('admin/images/svg/sidebar-icon/service-fill.svg') }}">
                            <span>{{ __('static.settings.settings') }}</span>
                        </a>
                    </li>
                @endcan
            @endcan
        </ul>
    </div>
</div>
<!-- Page Sidebar End -->
