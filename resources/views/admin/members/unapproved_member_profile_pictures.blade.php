@extends('admin.layouts.app')
@section('content')

<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Unapproved Profile Pictures')}}</h1>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
      <h5 class="mb-md-0 h6">{{ translate('Profile Pictures') }}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Photo')}}</th>
                    <th>{{translate('Member Code')}}</th>
                    <th>{{translate('Member Name')}}</th>
                    <th>{{translate('Approval')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $key => $user)
                    <tr>
                        <td>{{ $key + 1 + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>
                            @if(uploaded_asset($user->photo) != null)
                                <img class="img-md" src="{{ uploaded_asset($user->photo) }}" height="45px"  alt="{{translate('photo')}}">
                            @else
                                <img class="img-md" src="{{ static_asset('assets/img/avatar-place.png') }}" height="45px"  alt="{{translate('photo')}}">
                            @endif
                        </td>
                        <td>{{ $user->code }}</td>
                        <td>{{ $user->first_name.' '.$user->last_name}}</td>
                        <td>
                            @can('approve_profile_picrures')
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" id="status.{{ $key }}"
                                   onchange="approve_profile_image(this)" value="{{ $user->id }}" data-switch="success"/ >
                                <span></span>
                            </label>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $users->appends(request()->input())->links() }}
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        function approve_profile_image(el) {
            $.post('{{ route('approve_profile_image') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value
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