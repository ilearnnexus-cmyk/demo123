<div class="aiz-user-sidenav-wrap pt-4 sticky-top c-scrollbar-light position-relative z-1 shadow-none">
    <div class="absolute-top-left d-xl-none">
        <button class="btn btn-sm p-2" data-toggle="class-toggle" data-target=".aiz-mobile-side-nav"
            data-same=".mobile-side-nav-thumb">
            <i class="las la-times la-2x"></i>
        </button>
    </div>
    <div class="aiz-user-sidenav rounded overflow-hidden">
        <div class="px-4 text-center mb-4">
            <span class="avatar avatar-md mb-3">
                @if (Auth::user()->photo != null)
                    <img src="{{ uploaded_asset(Auth::user()->photo) }}">
                @else
                    <img src="{{ static_asset('assets/img/avatar-place.png') }}">
                @endif
            </span>
            <h4 class="h5 fw-600">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</h4>
        </div>
        <div class="text-center mb-3 px-3">
            <a href="{{ route('member_profile', Auth::user()->id) }}"
                class="btn btn-block btn-soft-primary">{{ translate('Public Profile') }}</a>
        </div>

        <div class="sidemnenu mb-3">
            <ul class="aiz-side-nav-list" data-toggle="aiz-side-menu">

                <li class="aiz-side-nav-item">
                    <a href="{{ route('dashboard') }}" class="aiz-side-nav-link {{ areActiveRoutes(['dashboard']) }}">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('gallery-image.index') }}" class="aiz-side-nav-link">
                        <i class="las la-image aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Gallery') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('happy-story.create') }}" class="aiz-side-nav-link">
                        <i class="las la-handshake aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Happy Story') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                        <i class="las la-shopping-basket aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Packages') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('packages') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Packages') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('package_purchase_history') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Package Purchase History') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

                @if (get_setting('wallet_system'))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('wallet.index') }}" class="aiz-side-nav-link">
                            <i class="las la-dollar-sign aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('My Wallet') }}</span>
                        </a>
                    </li>
                @endif

                @if (addon_activation('referral_system'))
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                            <i class="las la-shopping-basket aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Referral System') }}</span>
                            @if (env('DEMO_MODE') == 'On')
                                <span class="badge badge-inline badge-danger">{{ translate('Addon') }}</span>
                            @endif
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('my_referred_users') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Referred Users') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('my_referral_earnings') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Referral Earnings') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('wallet_withdraw_request_history') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Wallet Withdraw Requests') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li class="aiz-side-nav-item">
                    <a href="{{ route('all.messages') }}" class="aiz-side-nav-link">
                        <i class="las la-envelope aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Message') }}</span>
                    </a>
                </li>
                @if (addon_activation('support_tickets'))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('support-tickets.user_index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['support-tickets.user_index', 'support-tickets.user_ticket_create']) }}">
                            <i class="las la-life-ring aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Support Ticket') }}</span>
                            @if (env('DEMO_MODE') == 'On')
                                <span class="badge badge-inline badge-danger">{{ translate('Addon') }}</span>
                            @endif
                        </a>
                    </li>
                @endif
                <li class="aiz-side-nav-item">
                    <a href="{{ route('my_interests.index') }}" class="aiz-side-nav-link">
                        <i class="la la-heart-o aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('My Interest') }}</span>
                    </a>
                </li>
                @if (get_setting('profile_picture_privacy') == 'only_me' || get_setting('gallery_image_privacy') == 'only_me')
                    <li class="aiz-side-nav-item">
                        <a href="javascript:void(0);" class="aiz-side-nav-link">
                            <i class="las la-images aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Picture View Requests') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @if (get_setting('profile_picture_privacy') == 'only_me')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('profile-picture-view-request.index') }}"
                                        class="aiz-side-nav-link">
                                        <span
                                            class="aiz-side-nav-text">{{ translate('Profile Picture View Request') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (get_setting('gallery_image_privacy') == 'only_me')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('gallery-image-view-request.index') }}"
                                        class="aiz-side-nav-link">
                                        <span
                                            class="aiz-side-nav-text">{{ translate('Gallery Image View Request') }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                <li class="aiz-side-nav-item">
                    <a href="{{ route('my_shortlists') }}" class="aiz-side-nav-link">
                        <i class="las la-list aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Shortlist') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('my_ignored_list') }}" class="aiz-side-nav-link">
                        <i class="las la-ban aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Ignored User List') }}</span>
                    </a>
                </li>

                <li class="aiz-side-nav-item">
                    <a href="{{ route('member.change_password') }}" class="aiz-side-nav-link">
                        <i class="las la-key aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Change Password') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="{{ route('profile_settings') }}" class="aiz-side-nav-link">
                        <i class="las la-user aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Manage Profile') }}</span>
                    </a>
                </li>
                <li class="aiz-side-nav-item">
                    <a href="javascript:void(0);" class="aiz-side-nav-link" onclick="account_deactivation()">
                        @if (Auth::user()->deactivated == 0)
                            <i class="las la-lock aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Deactive Account') }}</span>
                        @else
                            <i class="las la-unlock aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Reactive Account') }}</span>
                        @endif
                    </a>
                </li>
                {{-- <li class="aiz-side-nav-item">
                    <a href="javascript:void(0);" class="aiz-side-nav-link" onclick="account_delete()">
                        <i class="las la-trash aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Delete Account') }}</span>
                    </a>
                </li> --}}
            </ul>
        </div>
        <div>
            <a href="javascript:void(0);" class="btn btn-block btn-primary" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="las la-sign-out-alt"></i>
                <span>{{ translate('Logout') }}</span>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </a>
        </div>
    </div>
</div>
