@extends('admin.layouts.app')
@section('content')

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
            <h5 class="mb-md-0 h6">{{ translate('Happy Stories') }}</h5>
        </div>
        <div class="col-md-4">
            <form class="" id="sort_happy_story" action="" method="GET">
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
                    <th>{{translate('Member Name')}}</th>
                    <th data-breakpoints="md">{{translate('Partner Name')}}</th>
                    <th data-breakpoints="md">{{translate('Post Time')}}</th>
                    <th>{{translate('Approval')}}</th>
                    <th class="text-right" width="20%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($happy_stories as $key => $happy_story)
                    <tr>
                        <td>{{ ($key+1) + ($happy_stories->currentPage() - 1)*$happy_stories->perPage() }}</td>
                        <td>{{ $happy_story->user->first_name.' '.$happy_story->user->last_name }}</td>
                        <td>{{ $happy_story->partner_name }}</td>
                        <td>{{ $happy_story->created_at}}</td>
                        <td>
                          <label class="aiz-switch aiz-switch-success mb-0">
                            <input onchange="update_status(this)" value="{{ $happy_story->id }}" type="checkbox"
                                @if($happy_story->approved == 1) checked @endif
                                @if(auth()->user()->cannot('approve_happy_story')) disabled @endif >
                            <span class="slider round"></span>
                        </td>
                        <td class="text-right">
                            @can('edit_happy_story')
                                <a href="{{ route('happy-story.edit', encrypt($happy_story->id)) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                            @endcan
                            @can('view_details_happy_story')
                                <a href="{{ route('happy-story.show', encrypt($happy_story->id)) }}" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $happy_stories->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection


@section('script')
<script>
  function sort_family_status(el){
      $('#sort_happy_story').submit();
  }
  function update_status(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('{{ route('happy_story_approval.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
            if(data == 1){
                AIZ.plugins.notify('success', '{{ translate('Happy story appeoval status updated successfully') }}');
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }
</script>
@endsection
