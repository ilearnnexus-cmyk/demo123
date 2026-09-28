@extends('admin.layouts.app')
@section('content')

<div class="alert" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
		<strong class="fs-15">{{ translate('Cron Job Add Instruction')}}:</strong>
		<br>
		<i class="text-danger">{{ translate('You Must Add A Cron Job To Check The Validity Of The Members Package.') }}</i>
		<ol class="mt-2">
        <li>
            {{ translate('To set a cron job, login to your cpanel and find the Cron Jobs option.') }}
        </li>
        <li>
            {{ translate('Go to Cron Jobs.') }}
        </li>
        <li>
            {{ translate('Add a new Cron Job.') }}
        </li>
        <li>
        	{{ translate('Select time period of Every Day') }}
        </li>
        <li>
            {{ translate('Set command as') }},  wget -O – http://your-domain-name.com/check_for_package_invalid
        </li>
    </ol>
</div>

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('Premium Packages')}}</h1>
		</div>
		@can('add_package')
		<div class="col-md-6 text-md-right">
			<a href="{{ route('packages.create') }}" class="btn btn-circle btn-primary">
				<span>{{translate('Add New Package')}}</span>
			</a>
		</div>
		@endcan
	</div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('All Packages')}}</h5>
            </div>
            <div class="card-body">
				<table class="table aiz-table mb-0">
					<thead>
						<tr>
							<th>#</th>
							<th>{{translate('Image')}}</th>
							<th>{{translate('Name')}}</th>
							<th data-breakpoints="md">{{translate('Price')}}</th>
							<th data-breakpoints="md">{{translate('Status')}}</th>
							<th class="text-right" width="10%">{{translate('Options')}}</th>
						</tr>
					</thead>
				<tbody>
				@foreach($packages as $key => $package)
				<tr>
				  <td>{{ ($key+1) + ($packages->currentPage() - 1)*$packages->perPage() }}</td>
					<td>
						<img class="img-md" src="{{ uploaded_asset($package->image) }}" height="45px" alt="{{translate('photo')}}">
					</td>
					<td>{{ $package->name }}</td>
					<td>{{ $package->price }}</td>
					<td>
						<label class="aiz-switch aiz-switch-success mb-0">
							<input type="checkbox" id="status.{{ $key }}"
							   onchange="update_package_activation_status(this)" value="{{ $package->id }}"
							   @if($package->active == 1) checked @endif data-switch="success"/ @if(auth()->user()->cannot('edit_package')) disabled @endif>
							<span></span>
						</label>
					</td>
					<td class="text-right">
						@can('edit_package')
							<a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{ route('packages.edit', encrypt($package->id)) }}" title="{{ translate('Edit') }}">
								<i class="las la-edit"></i>
							</a>
						@endcan
						@if($package->id != 1 && auth()->user()->can('delete_package'))
							<a href="javascript:void(0);" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('packages.destroy', $package->id)}}" title="{{ translate('Delete') }}">
								<i class="las la-trash"></i>
							</a>
						@endif
					</td>
				</tr>
				@endforeach
				</tbody>
				</table>
				<div class="aiz-pagination">
					{{ $packages->appends(request()->input())->links() }}
				</div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script>
        function update_package_activation_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('packages.update_package_activation_status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function (data) {
                if (data == 1) {
                    location.reload();
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
