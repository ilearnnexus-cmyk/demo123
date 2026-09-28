@extends('frontend.layouts.member_panel')
@section('panel_content')
    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Notifications') }}</h5>
            </div>
            <div class="col-md-3 text-right">
                <div class="btn-group mb-2">
                    <div class="btn-group">
                        <button type="button" class="btn py-0" data-toggle="dropdown"
                                aria-expanded="false">
                            <i class="las la-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('notification.mark_all_as_read') }}" class="dropdown-item">{{ translate('Mark all as read') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-raw">
                @if(!$notifications->isEmpty())
                    @foreach($notifications as $notification)
                        @php
                            $check = true;
                            $notify_data = json_decode($notification->data);
                            $user = \App\Models\User::where('id',$notify_data->notify_by)->first();
                        @endphp
                        @if($notify_data->type == 'express_interest')
                            @php
                                $interest_data = App\Models\ExpressInterest::where('id', $notify_data->info_id)->first();
                                if(empty($interest_data))
                                {
                                    $check = false;
                                }
                            @endphp
                        @endif
                        @if($check && $user != null)
                            <li class="list-group-item d-flex justify-content-between align-items-start hov-bg-soft-primary">
                                <a href="{{ route('notification_view', $notification->id) }}" class="media text-inherit">
                                    @php
                                        if($user->user_type == 'member'){
                                            $avatar_image = $user->member->gender == 1 ? 'assets/img/avatar-place.png' : 'assets/img/female-avatar-place.png';
                                        }
                                        $profile_picture_show = show_profile_picture($user);
                                    @endphp
                                    <span class="avatar avatar-sm mr-3">
                                        <img 
                                            @if ($profile_picture_show || $user->user_type == 'admin')
                                            src="{{ uploaded_asset($user->photo) }}"
                                            @else
                                            src="{{ static_asset($avatar_image) }}"
                                            @endif
                                        >
                                    </span>
                                    <div class="media-body">
                                        <p class="mb-1">{{ $notify_data->message }}</p>
                                        <small class="text-muted">{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                    </div>
                                </a>
                                @if($notification->read_at == null)
                                    <button class="btn p-0" data-toggle="tooltip" data-title="{{ translate('New') }}">
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
                            <h4>{{ translate('Nothing Found') }}</h4>
                        </div>
                    </li>
                @endif
            </ul>
            <div class="aiz-pagination aiz-pagination-center">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
@endsection
