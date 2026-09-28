@extends('frontend.layouts.member_panel')
@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Profile Picture View Requests') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>{{translate('Image')}}</th>
                      <th>{{translate('Name')}}</th>
                      <th>{{translate('Age')}}</th>
                      <th class="text-center">{{translate('Action')}}</th>
                  </tr>
              </thead>
              <tbody>
                    @foreach ($my_profile_pic_view_requests as $key => $my_profile_pic_view_request_id)
                        @php 
                            $my_profile_pic_view_request = \App\Models\ViewProfilePicture::where('id',$my_profile_pic_view_request_id->id)->first(); 
                            $profile_pic_view_requester = $my_profile_pic_view_request->requested_user;
                        @endphp

                        @if($profile_pic_view_requester != Null)
                            <tr>
                                <td>{{ ($key+1) + ($my_profile_pic_view_requests->currentPage() - 1)*$my_profile_pic_view_requests->perPage() }}</td>
                                <td>
                                    <a
                                        @if(get_setting('full_profile_show_according_to_membership') == 1 && Auth::user()->membership == 1)
                                            href="javascript:void(0);" onclick="package_update_alert()"
                                        @else
                                            href="{{ route('member_profile', $profile_pic_view_requester->id) }}"
                                        @endif
                                        class="text-reset c-pointer"
                                    >
                                        @php
                                            $avatar_image = $profile_pic_view_requester->member->gender == 1 ? 'assets/img/avatar-place.png' : 'assets/img/female-avatar-place.png';
                                        @endphp
                                        <img
                                            @if (show_profile_picture($profile_pic_view_requester))
                                            src="{{ uploaded_asset($profile_pic_view_requester->photo) }}"
                                            @else
                                            src="{{ static_asset($avatar_image) }}"
                                            @endif
                                            onerror="this.onerror=null;this.src='{{ static_asset($avatar_image) }}';"
                                            class="img-md" height="45px"
                                        >
                                    </a>
                                </td>
                                <td>
                                    <a
                                        @if(get_setting('full_profile_show_according_to_membership') == 1 && Auth::user()->membership == 1)
                                            href="javascript:void(0);" onclick="package_update_alert()"
                                        @else
                                            href="{{ route('member_profile', $profile_pic_view_requester->id) }}"
                                        @endif
                                        class="text-reset c-pointer"
                                    >
                                        {{ $profile_pic_view_requester->first_name.' '.$profile_pic_view_requester->last_name }}</td>
                                    </a>

                                <td>{{ \Carbon\Carbon::parse($profile_pic_view_requester->member->birthday)->age }}</td>
                                <td class="text-center">
                                    @if($my_profile_pic_view_request->status != 1)
                                        <a href="javascript:void(0);" onclick="accept_profile_pic_view_request({{ $my_profile_pic_view_request->id }})" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Accept') }}">
                                            <i class="las la-check"></i>
                                        </a>
                                        <a href="javascript:void(0);" onclick="reject_profile_pic_view_request({{ $my_profile_pic_view_request->id }})" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="{{ translate('Reject') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    @else
                                        <span class="badge badge-inline badge-success">{{translate('Accepted')}}</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
              </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $my_profile_pic_view_requests->links() }}
            </div>
        </div>
    </div>
@endsection
@section('modal')
    {{-- Profile Picture Accept modal--}}
    <div class="modal fade profile_picture_view_request_accept_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{translate('Profile Picture View Request Accept!')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
                    <form class="form-horizontal member-block" action="{{ route('profile_picture_view_request_accept') }}" method="POST">
                        @csrf
                        <input type="hidden" name="profile_pic_view_request_id" id="profile_pic_view_request_accept_id" value="">
                        <p class="mt-1">{{translate('Are you sure you want to accept this request?')}}</p>
                        <button type="button" class="btn btn-danger mt-2" data-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-info mt-2">{{translate('Confirm')}}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Profile Picture Reject Modal --}}
    <div class="modal fade profile_picture_view_request_reject_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{translate('Profile Picture View Request Reject !')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
                    <form class="form-horizontal member-block" action="{{ route('profile_picture_view_request_reject') }}" method="POST">
                        @csrf
                        <input type="hidden" name="profile_pic_view_request_id" id="profile_pic_view_request_reject_id" value="">
                        <p class="mt-1">{{translate('Are you sure you want to rejet his request?')}}</p>
                        <button type="button" class="btn btn-danger mt-2" data-dismiss="modal">{{translate('Cancel')}}</button>
                        <button type="submit" class="btn btn-info mt-2">{{translate('Confirm')}}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script type="text/javascript">

    function accept_profile_pic_view_request(id) {
        $('.profile_picture_view_request_accept_modal').modal('show');
        $('#profile_pic_view_request_accept_id').val(id);
    }

    function reject_profile_pic_view_request(id) {
        $('.profile_picture_view_request_reject_modal').modal('show');
        $('#profile_pic_view_request_reject_id').val(id);
    }

</script>
@endsection
