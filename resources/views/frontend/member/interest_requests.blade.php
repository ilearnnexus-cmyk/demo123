@extends('frontend.layouts.member_panel')
@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Interest Requests') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Image') }}</th>
                        <th>{{ translate('Name') }}</th>
                        <th>{{ translate('Age') }}</th>
                        <th class="text-center">{{ translate('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($interests as $key => $interest)
                        @php $interested_by = \App\Models\User::where('id',$interest->interested_by)->first(); @endphp
                        @if ($interested_by != null)
                            <tr id="interested_member_{{ $interested_by->id }}">
                                <td>{{ $key + 1 + ($interests->currentPage() - 1) * $interests->perPage() }}</td>
                                <td>
                                    <a @if (get_setting('full_profile_show_according_to_membership') == 1 && Auth::user()->membership == 1) href="javascript:void(0);" onclick="package_update_alert()"
                                        @else
                                            href="{{ route('member_profile', $interested_by->id) }}" @endif
                                        class="text-reset c-pointer">
                                        @if (uploaded_asset($interested_by->photo) != null)
                                            <img class="img-md" src="{{ uploaded_asset($interested_by->photo) }}"
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
                                            href="{{ route('member_profile', $interested_by->id) }}" @endif
                                        class="text-reset c-pointer">
                                        {{ $interested_by->first_name . ' ' . $interested_by->last_name }}
                                </td>
                                </a>

                                <td>{{ \Carbon\Carbon::parse($interested_by->member->birthday)->age }}</td>
                                <td class="text-center">
                                    @if ($interest->status != 1)
                                        <a href="javascript:void(0);" onclick="accept_interest({{ $interest->id }})"
                                            class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                            title="{{ translate('Accept') }}">
                                            <i class="las la-check"></i>
                                        </a>
                                        <a href="javascript:void(0);" onclick="reject_interest({{ $interest->id }})"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            title="{{ translate('Reject') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    @else
                                        <span class="badge badge-inline badge-success">{{ translate('Accepted') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $interests->links() }}
            </div>
        </div>
    </div>
@endsection
@section('modal')
    {{-- Interest Accept modal --}}
    <div class="modal fade interest_accept_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Interest Accept!') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
                    <form class="form-horizontal member-block" action="{{ route('accept_interest') }}" method="POST">
                        @csrf
                        <input type="hidden" name="interest_id" id="interest_accept_id" value="">
                        <p class="mt-1">{{ translate('Are you sure you want to accept this interest?') }}</p>
                        <button type="button" class="action-btn btn btn-danger mt-2"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="action-btn btn btn-info mt-2">{{ translate('Confirm') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Interest Reject Modal --}}
    <div class="modal fade interest_reject_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Interest Reject !') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
                    <form class="form-horizontal member-block" action="{{ route('reject_interest') }}" method="POST">
                        @csrf
                        <input type="hidden" name="interest_id" id="interest_reject_id" value="">
                        <p class="mt-1">{{ translate('Are you sure you want to rejet his interest?') }}</p>
                        <button type="button" class="btn btn-danger mt-2"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-info mt-2 action-btn">{{ translate('Confirm') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function accept_interest(id) {
            $('.interest_accept_modal').modal('show');
            $('#interest_accept_id').val(id);
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
