@extends('frontend.layouts.member_panel')
@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('My Interests') }}</h5>
            <a href="{{ route('interest_requests') }}"
                class="mb-0 h6 btn btn-primary">{{ translate('Interest Requests') }}</a>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Image') }}</th>
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('Age') }}</th>
                        <th class="text-center">{{ translate('Status') }}</th>
                        <th class="text-center">{{ translate('Action') }}</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($interests as $key => $interest_id)
                        @php
                            $interest = \App\Models\ExpressInterest::where('id', $interest_id->id)->first();
                        @endphp

                        <tr id="interested_member_{{ $interest->user_id }}">
                            <td>{{ $key + 1 + ($interests->currentPage() - 1) * $interests->perPage() }}</td>
                            <td>
                                <a @if (get_setting('full_profile_show_according_to_membership') == 1 && Auth::user()->membership == 1) href="javascript:void(0);" onclick="package_update_alert()"
                                @else
                                    href="{{ route('member_profile', $interest->user_id) }}" @endif
                                    class="text-reset c-pointer">
                                    @if (uploaded_asset($interest->user->photo) != null)
                                        <img class="img-md" src="{{ uploaded_asset($interest->user->photo) }}"
                                            height="45px" alt="{{ translate('photo') }}">
                                    @else
                                        <img class="img-md" src="{{ static_asset('assets/img/avatar-place.png') }}"
                                            height="45px" alt="{{ translate('photo') }}">
                                    @endif
                                </a>
                            </td>
                            <td>
                                <a @if (get_setting('full_profile_show_according_to_membership') == 1 && Auth::user()->membership == 1) href="javascript:void(0);" onclick="package_update_alert()"
                                @else
                                    href="{{ route('member_profile', $interest->user_id) }}" @endif
                                    class="text-reset c-pointer">
                                    {{ $interest->user->first_name . ' ' . $interest->user->last_name }}
                                </a>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($interest->user->member->birthday)->age }}</td>
                            <td class="text-center">
                                @if ($interest->status == 1)
                                    <span class="badge badge-inline badge-success">{{ translate('Approved') }}</span>
                                @else
                                    <span class="badge badge-inline badge-info">{{ translate('Pending') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="javascript:void(0);" onclick="reject_interest({{ $interest->id }})"
                                    class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $interests->links() }}
            </div>
        </div>
    </div>
@endsection

  {{-- Interest Reject Modal --}}
  <div class="modal fade interest_reject_modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6">{{translate('Interest Reject !')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <form class="form-horizontal member-block" action="{{ route('reject_interest') }}" method="POST">
                    @csrf
                    <input type="hidden" name="interest_id" id="interest_reject_id" value="">
                    <p class="mt-1">{{translate('Are you sure you want to rejet his interest?')}}</p>
                    <button type="button" class="btn btn-danger mt-2" data-dismiss="modal">{{translate('Cancel')}}</button>
                    <button type="submit" class="btn btn-info mt-2 action-btn">{{translate('Confirm')}}</a>
                </form>
            </div>
        </div>
    </div>
</div>

@section('modal')
    @include('modals.package_update_alert_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function package_update_alert() {
            $('.package_update_alert_modal').modal('show');
        }

        function reject_interest(id) {
        $('.interest_reject_modal').modal('show');
        $('#interest_reject_id').val(id);
    }
  // Prevent submitting multiple button
  $('form').bind('submit', function(e) {
            if ($(".action-btn").attr('attempted') == 'true') {
                //stop submitting the form and disable the submit button.
                e.preventDefault();
                $(".action-btn").attr("disable", true);
            } else {
                $(".action-btn").attr("attempted", 'true');
            }
        });
    
    </script>
@endsection
