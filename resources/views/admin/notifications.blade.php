@extends('admin.layouts.app')
@section('content')
    <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
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
                                                      <small class="text-muted">{{ $notify_data->message }}</small>
                                                      <br>
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
                                @endif
                            </ul>
                            <div class="aiz-pagination">
                                {{ $notifications->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
