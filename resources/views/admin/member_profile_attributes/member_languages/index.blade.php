@extends('admin.layouts.app')
@section('content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Member Languages')}}</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('All Member Languages') }}</h5>
                </div>
                <div class="col-md-4">
                    <form class="" id="sort_member_languages" action="" method="GET">
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
                            <th class="text-right" width="20%">{{translate('Options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($languages as $key => $language)
                            <tr>
                                <td>{{ ($key+1) + ($languages->currentPage() - 1)*$languages->perPage() }}</td>
                                <td>{{$language->name}}</td>
                                <td class="text-right">
                                  @can('edit_member_language')
                                      <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="javascript:void(0);" onclick="language_modal('{{ route('member-languages.edit', encrypt($language->id) )}}')" title="{{ translate('Edit') }}">
                                          <i class="las la-edit"></i>
                                      </a>
                                  @endcan
                                  @can('delete_member_language')
                                      <a href="javascript:void(0);" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('member-languages.destroy', $language->id)}}" title="{{ translate('Delete') }}">
                                          <i class="las la-trash"></i>
                                      </a>
                                  @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $languages->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
    @can('add_member_language')
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Add New Member Language')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('member-languages.store') }}" method="POST" >
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">{{translate('Name')}}</label>
                        <input type="text" id="name" name="name" placeholder="{{ translate('Language Name') }}"
                               class="form-control" required>
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
    </div>
    @endcan
</div>
@endsection
@section('modal')
    @include('modals.create_edit_modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script>
      function sort_member_languages(el){
          $('#sort_member_languages').submit();
      }

       function language_modal(url){
            $.get(url, function(data){
                $('.create_edit_modal_content').html(data);
                $('.create_edit_modal').modal('show');
            });
        }
    </script>
@endsection
