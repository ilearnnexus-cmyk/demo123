@extends('admin.layouts.app')
@section('content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Countries')}}</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('All Countries') }}</h5>
                </div>
                <div class="col-md-4">
                    <form class="" id="sort_countries" action="" method="GET">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('Name')}}</th>
                            <th>{{ translate('Code') }}</th>
                            <th>{{ translate('Show/Hide') }}</th>
                            {{-- <th class="text-right" width="20%">{{translate('Options')}}</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($countries as $key => $country)
                            <tr>
                                <td>{{ ($key+1) + ($countries->currentPage() - 1)*$countries->perPage() }}</td>
                                <td>{{ $country->name }}</td>
                                <td>{{ $country->code }}</td>
                                <td>
                                    @can('edit_country')
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                          <input onchange="update_status(this)" value="{{ $country->id }}" type="checkbox" <?php if($country->status == 1) echo "checked";?> >
                                          <span class="slider round"></span>
                                        </label>
                                    @endcan
                                </td>
                                {{-- <td class="text-right">
                                    <a href="javascript:void(0);" onclick="country_modal('{{ route('countries.edit', encrypt($country->id) )}}')" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-href="{{route('countries.destroy', $country->id)}}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $countries->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Add New Country')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('countries.store') }}" method="POST" >
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">{{translate('Name')}}</label>
                        <input type="text" id="name" name="name" placeholder="{{ translate('Country Name') }}" class="form-control" required>
                         @error('name')
                             <small class="form-text text-danger">{{ $message }}</small>
                         @enderror
                    </div>
                    <div class="form-group mb-3 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
</div>
@endsection

@section('modal')
    @include('modals.create_edit_modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script>

      function sort_countries(el){
          $('#sort_countries').submit();
      }

      function update_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('countries.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Country status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

      // function country_modal(url){
      //     $.get(url, function(data){
      //         $('.create_edit_modal').modal('show');
      //         $('.create_edit_modal_content').html(data);
      //     });
      // }
    </script>
@endsection
