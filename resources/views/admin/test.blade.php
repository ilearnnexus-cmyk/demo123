@if($notifications->count() > 0)
    @foreach ($notifications as $key => $notification)
        @php
            $user_data = '';
            $notify_data = json_decode($notification->data);
            $link = env('APP_URL').$notify_data->url;
        @endphp
        @if($notify_data->type == 'express_interest')
            @php
                $userId = \App\Models\ExpressInterest::where('id', $notify_data->info_id)->first()->interested_by;
                $user_data = \App\Models\User::where('id',$userId)->first();
            @endphp
        @elseif ($notify_data->type == 'accept_interest' || $notify_data->type == 'reject_interest')
            @php
                $userId = \App\Models\ExpressInterest::where('id', $notify_data->info_id)->first()->user_id;
                $user_data = \App\Models\User::where('id',$userId)->first();
            @endphp
        @endif

        <li class="list-group-item d-flex justify-content-between align-items-start hov-bg-soft-primary">
            <a href="#" class="media text-inherit">

                <span class="avatar avatar-sm mr-3">
                    <img src="{{ uploaded_asset($user_data->photo) }}">
                </span>
                <div class="media-body">
                    <p class="mb-1">{{ $user_data->first_name.' '.$user_data->last_name }}</p>
                    <small class="text-muted">
                        {{ $notify_data->message }}
                    </small>
                </div>
            </a>
            <button class="btn p-0">
                <span class="badge badge-md  badge-dot badge-circle badge-primary"></span>
            </button>
        </li>
    @endforeach
@else
    <li class="list-group-item">
        <div class="text-center">
            <i class="las la-frown la-4x mb-4 opacity-40"></i>
            <h4 class="h5">{{ translate('No Notifications') }}</h4>
        </div>
    </li>
@endif
