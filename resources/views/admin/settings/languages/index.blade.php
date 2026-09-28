@extends('admin.layouts.app')
@section('content')

@can('change_default_language')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Default Language') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('env_key_update.update') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{ translate('Default Language') }}</label>
                        </div>
                        <input type="hidden" name="types[]" value="DEFAULT_LANGUAGE">
                        <div class="col-lg-6">
                            <select class="form-control aiz-selectpicker" name="DEFAULT_LANGUAGE">
                                @foreach (\App\Models\Language::all() as $key => $language)
                                    <option value="{{ $language->code }}" <?php if(env('DEFAULT_LANGUAGE') == $language->code) echo 'selected'?> >{{ $language->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
<div class="row">
    <div class="@if(auth()->user()->can('add_languages')) col-lg-7 @else col-lg-12 @endif">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('All Languages')}}</h5>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('Name')}}</th>
                            <th>{{translate('Code')}}</th>
                            <th>{{translate('RTL')}}</th>
                            <th class="text-right" width="20%">{{translate('Options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($languages as $key => $language)
                            <tr>
                                <td>{{ ($key+1) + ($languages->currentPage() - 1)*$languages->perPage() }}</td>
                                <td>{{ $language->name }}</td>
                                <td>{{ $language->code }}</td>
                                <td>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="update_rtl_status(this)" value="{{ $language->id }}" type="checkbox"
                                            @if($language->rtl == 1) checked @endif
                                            @if(auth()->user()->cannot('edit_languages')) disabled @endif
                                        >
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td class="text-right">
                                    @can('translate_languages')
                                        <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{route('languages.show', encrypt($language->id))}}" title="{{ translate('Translation') }}">
                                            <i class="las la-language"></i>
                                        </a>
                                    @endcan
                                    @can('edit_languages')
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('languages.edit', $language->id)}}" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                    @endcan
                                    @if($language->code != 'en' && auth()->user()->can('delete_languages'))
                                        <a href="javascript:void(0);" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('languages.destroy', $language->id)}}" title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    @endif
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
    @can('add_languages')
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Add New Language')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('languages.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-2">
                            <label class="control-label">{{ translate('Name') }}</label>
                        </div>
                        <div class="col-lg-10">
                            <input type="text" id="name" name="name" placeholder="{{ translate('Eg. English') }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-2">
                            <label class="control-label">{{ translate('Code') }}</label>
                        </div>
                        <div class="col-lg-10">
                            <select class="form-control aiz-selectpicker mb-2 mb-md-0" name="code" data-live-search="true" >
                                @foreach(\File::files(base_path('public/assets/img/flags')) as $path)
                                    <option value="{{ pathinfo($path)['filename'] }}" data-content="<div class=''><img src='{{ static_asset('assets/img/flags/'.pathinfo($path)['filename'].'.png') }}' class='mr-2'><span>{{ strtoupper(pathinfo($path)['filename']) }}</span></div>"></option>
                                @endforeach
                            </select>
                        </div>
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
    <script type="text/javascript">

        function language_modal(url){
             $.get(url, function(data){
                 $('.create_edit_modal_content').html(data);
                 $('.create_edit_modal').modal('show');
             });
         }

         function update_rtl_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('languages.update_rtl_status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

    </script>
@endsection
