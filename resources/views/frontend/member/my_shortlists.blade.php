@extends('frontend.layouts.member_panel')
@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('My Shortlists') }}</h5>
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
                  @foreach ($shortlists as $key => $shortlist)
                      @if($shortlist->user != null)
                      <tr id="shortlisted_member_{{ $shortlist->user_id }}">
                          <td>{{ ($key+1) + ($shortlists->currentPage() - 1)*$shortlists->perPage() }}</td>
                          <td>
                            <a
                              @if(get_setting('full_profile_show_according_to_membership') == 1 && Auth::user()->membership == 1)
                                  href="javascript:void(0);" onclick="package_update_alert()"
                              @else
                                  href="{{ route('member_profile', $shortlist->user_id) }}"
                              @endif
                              class="text-reset c-pointer"
                            >
                                @if(uploaded_asset($shortlist->user->photo) != null)
                                    <img class="img-md" src="{{ uploaded_asset($shortlist->user->photo) }}" height="45px"  alt="{{translate('photo')}}">
                                @else
                                    <img class="img-md" src="{{ static_asset('assets/img/avatar-place.png') }}" height="45px"  alt="{{translate('photo')}}">
                                @endif
                            </a>
                          </td>
                          <td>
                              <a
                                @if(get_setting('full_profile_show_according_to_membership') == 1 && Auth::user()->membership == 1)
                                    href="javascript:void(0);" onclick="package_update_alert()"
                                @else
                                    href="{{ route('member_profile', $shortlist->user_id) }}"
                                @endif
                                class="text-reset c-pointer"
                              >
                                {{ $shortlist->user->first_name.' '.$shortlist->user->last_name }}
                            </a>
                          </td>
                          <td>{{ \Carbon\Carbon::parse($shortlist->user->member->birthday)->age }}</td>
                          @if(get_setting('member_spiritual_and_social_background_section') == 'on')
                          <td>
                            @if(!empty($shortlist->user->spiritual_backgrounds->religion_id))
                                {{ $shortlist->user->spiritual_backgrounds->religion->name }}
                            @endif
                          </td>
                          @endif
                          @if(get_setting('member_present_address_section') == 'on')
                          <td>
                            @php
                                $present_address = \App\Models\Address::where('type','present')->where('user_id', $shortlist->user_id)->first();
                            @endphp
                            @if(!empty($present_address->country_id))
                                {{ $present_address->country->name }}
                            @endif
                          </td>
                          @endif
                          @if(get_setting('member_language_section') == 'on')
                          <td>
                            @if($shortlist->user->member->mothere_tongue != null)
                                {{ \App\Models\MemberLanguage::where('id',$shortlist->user->member->mothere_tongue)->first()->name }}
                            @endif
                          </td>
                          @endif
                          <td class="text-right">
                              @php
                                $expressed_interest = \App\Models\ExpressInterest::where('user_id', $shortlist->user_id)->where('interested_by',Auth::user()->id)->first();
                                $received_expressed_interest = \App\Models\ExpressInterest::where('user_id', Auth::user()->id)
                                                                ->where('interested_by', $shortlist->user_id)
                                                                ->first();
                              @endphp
                              @if(empty($expressed_interest) && empty($received_expressed_interest))
                                <a href="avascript:void(0);" onclick="express_interest({{ $shortlist->user_id }})" id="interest_a_id_{{$shortlist->user_id}}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Express Interest') }}">
                                    <i class="las la-heart"></i>
                                </a>
                              @elseif($received_expressed_interest)
                                @if($received_expressed_interest->status == 0)
                                  <a href="{{ route('interest_requests') }}" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Response to Interest') }}">
                                    <i class="las la-heart"></i>
                                  </a>
                                @else
                                  <a href="avascript:void(0);" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('You Accepted Interest of This Member') }}">
                                    <i class="las la-heart"></i>
                                  </a>
                                @endif
                              @else
                                <a href="avascript:void(0);" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Interest Expressed') }}">
                                    <i class="las la-heart"></i>
                                </a>
                              @endif
                              <a onclick="remove_shortlist({{ $shortlist->user_id }})" class="btn btn-soft-danger btn-icon btn-circle btn-sm" title="{{ translate('Remove') }}">
                                  <i class="las la-trash-alt"></i>
                              </a>
                          </td>
                      </tr>
                    @endif

                  @endforeach
              </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $shortlists->links() }}
            </div>
        </div>
    </div>
@endsection

@section('modal')
  @include('modals.confirm_modal')
  @include('modals.package_update_alert_modal')
@endsection

@section('script')
<script type="text/javascript">
    function remove_shortlist(id) {
      $.post('{{ route('member.remove_from_shortlist') }}',
        {
          _token: '{{ csrf_token() }}',
          id: id
        },
        function (data) {
          if (data == 1) {
            $("#shortlisted_member_"+id).hide();
            AIZ.plugins.notify('success', '{{translate('You Have Removed This Member From Shortlist')}}');
          }
          else {
            AIZ.plugins.notify('danger', '{{translate('Something went wrong')}}');
          }
        }
      );
    }

    // Express Interest
    var package_validity = {{ package_validity(Auth::user()->id) }};

    function express_interest(id)
    {
      var user_id = {{ Auth::user()->id }}
      $.post('{{ route('user.remaining_package_value') }}', {_token:'{{ csrf_token() }}', id:user_id, colmn_name:'remaining_interest' }, function(data){
          
          var remaining_interest = data;
          if(!package_validity || remaining_interest < 1){
              $('.package_update_alert_modal').modal('show');
          }
          else{
            $('.confirm_modal').modal('show');
            $("#confirm_modal_title").html("{{ translate('Confirm Express Interest') }}");
            $("#confirm_modal_content").html("<p class='fs-14'>{{translate('Remaining Express Interests')}}: "+remaining_interest+" {{translate('Times')}}</p><small class='text-danger fs-12'>{{translate('**N.B. Expressing An Interest Will Cost 1 From Your Remaining Interests**')}}</small>");
            $("#confirm_button").attr("onclick","do_express_interest("+id+")");
          }
      });
    }

    function do_express_interest(id){
      $('.confirm_modal').modal('hide');
      $("#interest_a_id_"+id).removeAttr("onclick");
      $.post('{{ route('express-interest.store') }}',
        {
          _token: '{{ csrf_token() }}',
          id: id
        },
        function (data) {
          if (data) {
            $("#interest_a_id_"+id).attr("class","btn btn-soft-success btn-icon btn-circle btn-sm");
            $("#interest_a_id_"+id).attr("title","{{ translate('Interest Expressed') }}");
            AIZ.plugins.notify('success', '{{translate('Interest Expressed Sucessfully')}}');
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
