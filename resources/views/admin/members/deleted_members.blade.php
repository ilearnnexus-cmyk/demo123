@extends('admin.layouts.app')
@section('content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Deleted Members')}}</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header row gutters-5">
  				<div class="col text-center text-md-left">
  					<h5 class="mb-md-0 h6">{{ translate('All Deleted Members') }}</h5>
  				</div>
  				<div class="col-md-3">
            <form class="" id="sort_members" action="" method="GET">
  						<div class="input-group input-group-sm">
  					  		<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type first name / last name / ID & Enter') }}">
  						</div>
  					</form>
  				</div>
		    </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('Image')}}</th>
                            <th>{{translate('Member Id')}}</th>
                            <th data-breakpoints="md">{{translate('Name')}}</th>
                            <th data-breakpoints="md">{{translate('Membership')}}</th>
                            <th data-breakpoints="md">{{translate('Approval Status')}}</th>
                            <th data-breakpoints="md">{{translate('Profile Reported')}}</th>
                            <th data-breakpoints="md">{{translate('Member Science')}}</th>
                            <th data-breakpoints="md">{{translate('Member Status')}}</th>
                            <th class="text-right" width="10%">{{translate('Options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deleted_members as $key => $deleted_member)
                            <tr>
                                <td>{{ ($key+1) + ($deleted_members->currentPage() - 1)*$deleted_members->perPage() }}</td>
                                <td>
                                    @if(uploaded_asset($deleted_member->photo) != null)
                                        <img class="img-md" src="{{ uploaded_asset($deleted_member->photo) }}" height="45px" alt="{{translate('photo')}}">
                                    @else
                                        <img class="img-md" src="{{ static_asset('assets/img/avatar-place.png') }}" height="45px"  alt="{{translate('photo')}}">
                                    @endif
                                </td>
                                <td>{{ $deleted_member->code }}</td>
                                <td>{{ $deleted_member->first_name.' '.$deleted_member->last_name }}</td>
                                <td>
                                    <span class="badge badge-inline badge-info">
                                        {{ $deleted_member->membership == 1 ? translate('Free') : translate('Premium') }}
                                    </span>
                                <td>
                                    @if($deleted_member->approved == 1)
                                        <span class="badge badge-inline badge-success">{{translate('Approved')}}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{translate('Pending')}}</span>
                                    @endif
                                </td>
                                <td>
                                  @if($deleted_member->reported_users->count() > 0)
                                    <a href="{{ route('reported_members', $deleted_member->id) }}" class="badge badge-inline badge-danger" title="{{ translate('View Reports') }}">{{ $deleted_member->reported_users->count() }}</a>
                                  @else
                                    0
                                  @endif
                                </td>
                                <td>{{ date('d-m-Y', strtotime($deleted_member->created_at)) }}</td>
                                <td>
                                    @if($deleted_member->deactivated == 0)
                                        <span class="badge badge-inline badge-success">{{translate('Active')}}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{translate('Deactivated')}}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @can ('restore_member')
                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm" href="{{route('restore_deleted_member', $deleted_member->id)}}" title="{{ translate('Restore') }}">
    		                                <i class="las la-check-circle"></i>
    		                            </a>
                                    @endcan
                                    <a href="javascript:void(0);" data-href="{{route('members.permanently_delete', $deleted_member->id)}}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="{{ translate('Permanently Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    {{ $deleted_members->links() }}
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
  @include('modals.delete_modal')
  <div id="restore-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6">{{translate('Restore Confirmation')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mt-1">{{translate('Are you sure to restore this member?')}}</p>
                <button type="button" class="btn btn-link mt-2" data-dismiss="modal">{{translate('Cancel')}}</button>
                <a id="restore-link" class="btn btn-primary mt-2">{{translate('Restore')}}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript">

        function sort_members(el){
            $('#sort_members').submit();
        }

        $(".confirm-restore").click(function(e) {
            var url = $(this).data("href");
            $("#restore-modal").modal("show");
            $("#restore-link").attr("href", url);
        });
    </script>
@endsection
