@extends('frontend.layouts.member_panel')
@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Ignored Members') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>{{translate('Image')}}</th>
                      <th>{{translate('Name')}}</th>
                      <th data-breakpoints="lg">{{translate('Age')}}</th>
                      @if(get_setting('member_spiritual_and_social_background_section') == 'on')
                      <th data-breakpoints="lg">{{translate('Religion')}}</th>
                      @endif
                      @if(get_setting('member_present_address_section') == 'on')
                      <th data-breakpoints="lg">{{translate('Location')}}</th>
                      @endif
                      @if(get_setting('member_language_section') == 'on')
                      <th data-breakpoints="lg">{{translate('Mother  Tongue')}}</th>
                      @endif
                      <th class="text-right" data-breakpoints="lg">{{translate('Options')}}</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach ($ignored_members as $key => $ignored_member)
                      @if($ignored_member->user != null)
                          <tr id="ignored_member_{{ $ignored_member->user_id }}">
                              <td>{{ ($key+1) + ($ignored_members->currentPage() - 1)*$ignored_members->perPage() }}</td>
                              <td>
                                  @if(uploaded_asset($ignored_member->user->photo) != null)
                                      <img class="img-md" src="{{ uploaded_asset($ignored_member->user->photo) }}" height="45px"  alt="{{translate('photo')}}">
                                  @else
                                      <img class="img-md" src="{{ static_asset('assets/img/avatar-place.png') }}" height="45px"  alt="{{translate('photo')}}">
                                  @endif
                              </td>
                              <td>
                                {{ $ignored_member->user->first_name.' '.$ignored_member->user->last_name }}
                              </td>
                              <td>{{ \Carbon\Carbon::parse($ignored_member->user->member->birthday)->age }}</td>
                              @if(get_setting('member_spiritual_and_social_background_section') == 'on')
                              <td>
                                @if(!empty($ignored_member->user->spiritual_backgrounds->religion_id))
                                    {{ $ignored_member->user->spiritual_backgrounds->religion->name }}
                                @endif
                              </td>
                              @endif
                              @if(get_setting('member_present_address_section') == 'on')
                              <td>
                                @php
                                    $present_address = \App\Models\Address::where('type','present')->where('user_id', $ignored_member->user_id)->first();
                                @endphp
                                @if(!empty($present_address->country_id))
                                    {{ $present_address->country->name }}
                                @endif
                              </td>
                              @endif
                              @if(get_setting('member_language_section') == 'on')
                              <td>
                                @if($ignored_member->user->member->mothere_tongue != null)
                                    {{ \App\Models\MemberLanguage::where('id',$ignored_member->user->member->mothere_tongue)->first()->name }}
                                @endif
                              </td>
                              @endif
                              <td class="text-right">
                                  <a onclick="remove_from_ignored_list({{ $ignored_member->user_id }})" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Remove From Ignore List') }}">
                                      <i class="las la-trash"></i>
                                  </a>
                              </td>
                          </tr>
                      @endif
                  @endforeach
              </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $ignored_members->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
  @include('modals.package_update_alert_modal')
@endsection

@section('script')
<script type="text/javascript">
    function remove_from_ignored_list(id) {
      $.post('{{ route('member.remove_from_ignored_list') }}',
        {
          _token: '{{ csrf_token() }}',
          id: id
        },
        function (data) {
          if (data == 1) {
            $("#ignored_member_"+id).hide();
            AIZ.plugins.notify('success', '{{translate('You Have Removed This Member From Your Ignored list')}}');
          }
          else {
            AIZ.plugins.notify('danger', '{{translate('Something went wrong')}}');
          }
        }
      );
    }

    function package_update_alert(){
      $('.package_update_alert_modal').modal('show');
    }

</script>
@endsection
