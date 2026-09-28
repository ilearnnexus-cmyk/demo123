<div class="aiz-topbar px-15px px-lg-25px d-flex align-items-stretch justify-content-between">
    <div class="d-xl-none d-flex">
        <div class="aiz-topbar-nav-toggler d-flex align-items-center justify-content-start mr-2 mr-md-3" data-toggle="aiz-mobile-nav">
            <button class="aiz-mobile-toggler">
                <span></span>
            </button>
        </div>
        <div class="aiz-topbar-logo-wrap d-flex align-items-center justify-content-start">
            <a href="{{ route('admin.dashboard') }}" class="d-block">
                <img src="{{ uploaded_asset(get_setting('system_logo')) }}" class="img-fluid" height="45">
            </a>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-stretch flex-grow-xl-1">
        <div class="d-none d-md-flex justify-content-around align-items-center align-items-stretch">
            
            <div class="d-none d-md-flex justify-content-around align-items-center align-items-stretch">
              <div class="aiz-topbar-item">
                  <div class="d-flex align-items-center">
                      <a class="btn btn-icon btn-circle btn-light" href="{{ route('home')}}" target="_blank" title="{{ translate('Browse Website') }}">
                          <i class="las la-globe"></i>
                      </a>
                  </div>
              </div>
          </div>
          <div class="d-flex justify-content-around align-items-center align-items-stretch ml-3">
                <div class="aiz-topbar-item">
                    <div class="d-flex align-items-center">
                        <a class="btn btn-soft-danger btn-sm d-flex align-items-center" href="{{ route('cache.clear')}}">
                            <i class="las la-hdd fs-20"></i>
                            <span class="fw-500 ml-1 mr-0 d-none d-md-block">{{ translate('Clear Cache') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-around align-items-center align-items-stretch">
            @php
                $notifications = \App\Models\Notification::latest()->where('notifiable_id',Auth()->user()->id)->take(10)->get();
                $unseen_notification = \App\Models\Notification::where('notifiable_id',Auth()->user()->id)->where('read_at',null)->count();
            @endphp

            <div class="aiz-topbar-item ml-2">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="btn btn-icon p-1">
                            <span class=" position-relative d-inline-block">
                                <i class="las la-bell la-2x"></i>
                                @if($unseen_notification > 0)
                                    <span class="badge badge-circle badge-primary position-absolute absolute-top-right">
                                        {{ $unseen_notification }}
                                    </span>
                                @endif
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-lg py-0">
                        <div class="p-3 bg-light border-bottom">
                            <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                        </div>
                        <ul class="list-group c-scrollbar-light overflow-auto" style="max-height:300px;">
                            @if($notifications->count() > 0)
                                @foreach ($notifications as $key => $notification)
                                    @php
                                        $notify_data = json_decode($notification->data);
                                        $user_data = \App\Models\User::where('id',$notify_data->notify_by)->first();
                                    @endphp
                                    @if(!empty($user_data))
                                    <li class="list-group-item d-flex justify-content-between align-items-start hov-bg-soft-primary">
                                        <a href="{{ route('notification_view', $notification->id) }}" class="media text-inherit">
                                            <span class="avatar avatar-sm mr-3">
                                                @if(!empty(uploaded_asset($user_data->photo)))
                                                    <img src="{{ uploaded_asset($user_data->photo) }}">
                                                @else
                                                    <img src="{{ static_asset('assets/img/avatar-place.png') }}">
                                                @endif
                                            </span>
                                            <div class="media-body">
                                                <p class="mb-1">{{ $user_data->first_name.' '.$user_data->last_name }}</p>
                                                <small class="text-muted">
                                                    {{ $notify_data->message }}
                                                </small>
                                            </div>
                                        </a>
                                        @if($notification->read_at == null)
                                            <button class="btn p-0">
                                                <span class="badge badge-md  badge-dot badge-circle badge-primary"></span>
                                            </button>
                                        @endif
                                    </li>
                                    @endif
                                @endforeach
                            @else
                                <li class="list-group-item">
                                    <div class="text-center">
                                        <i class="las la-frown la-4x mb-4 opacity-40"></i>
                                        <h4 class="h5">{{ translate('No Notifications') }}</h4>
                                    </div>
                                </li>
                            @endif
                        </ul>
                        <div class="border-top">
                            <a href="{{ route('admin.notifications') }}" class="btn text-reset btn-block">{{ translate('View All Notifications') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- language --}}
            @php
                if(Session::has('locale')){
                    $locale = Session::get('locale', Config::get('app.locale'));
                }
                else{
                    $locale = env('DEFAULT_LANGUAGE');
                }
            @endphp
            <!-- <div class="aiz-topbar-item ml-2">
                <div class="align-items-stretch d-flex dropdown " id="lang-change">
                    <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="btn btn-icon">
                            <img src="{{ static_asset('assets/img/flags/'.$locale.'.png') }}" height="11">
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-xs">

                        @foreach (\App\Models\Language::all() as $key => $language)
                            <li>
                                <a href="javascript:void(0)" data-flag="{{ $language->code }}" class="dropdown-item @if($locale == $language->code) active @endif">
                                    <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" class="mr-2">
                                    <span class="language">{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div> -->
            <div class="aiz-topbar-item ml-2">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow text-dark" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <span class="mr-md-2">
                                <img src="{{ uploaded_asset(Auth::user()->photo) }}" class="size-35px rounded-circle img-fit" height="36" width="36" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                            </span>
                            <span class="d-none d-md-block">
                                <span class="d-block fw-500">{{Auth::user()->first_name.' '.Auth::user()->last_name}}</span>
                                <span class="d-block small opacity-60">{{Auth::user()->user_type}}</span>
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-md">
                        <a href="{{ route('profile.index')}}" class="dropdown-item">
                            <i class="las la-user-circle"></i>
                            <span>{{translate('Manage Profile')}}</span>
                        </a>

                        <a href="javascript:void(0);" class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            <i class="las la-sign-out-alt"></i>
                            <span>{{translate('Logout')}}</span>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </a>
                    </div>
                </div>
            </div><!-- .aiz-topbar-item -->
        </div>
    </div>
</div><!-- .aiz-topbar -->
