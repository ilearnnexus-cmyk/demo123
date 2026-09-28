@if($notifications->count() > 0)
    @foreach ($notifications as $key => $notification)
        @php
            $check = 'done';
            $notify_data = json_decode($notification->data);
            $user_data = \App\Models\User::where('id',$notify_data->notify_by)->first();
        @endphp
        @if($notify_data->type == 'express_interest')
            @php
                $interest_data = App\Models\ExpressInterest::where('id', $notify_data->info_id)->first();
                if(empty($interest_data))
                {
                    $check = 'not_done';
                }
            @endphp
        @endif
        @if($check == 'done' && !empty($user_data))
            <li class="list-group-item d-flex justify-content-between align-items-start hov-bg-soft-primary">
                <a href="{{ route('notification_view', $notification->id) }}" class="media text-inherit">
                    <span class="avatar avatar-sm mr-3">
                        @php
                            $avatar_image = $user_data->member->gender == 1 ? 'assets/img/avatar-place.png' : 'assets/img/female-avatar-place.png';
                            $profile_picture_show = show_profile_picture($user_data);
                        @endphp
                        <img
                            @if ($profile_picture_show)
                            src="{{ uploaded_asset($user_data->photo) }}"
                            @else
                            src="{{ static_asset($avatar_image) }}"
                            @endif
                            onerror="this.onerror=null;this.src='{{ static_asset($avatar_image) }}';"
                        >
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
