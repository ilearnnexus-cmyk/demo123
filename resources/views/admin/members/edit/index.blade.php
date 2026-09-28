@extends('admin.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Member Profile Update')}}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="v-pills-tab-1" data-toggle="pill" href="#introduction" role="tab" aria-controls="v-pills-home" aria-selected="true">{{translate('Introduction')}}</a>
                                <a class="nav-link" id="v-pills-tab-2" data-toggle="pill" href="#basic_information" role="tab" aria-controls="v-pills-profile" aria-selected="false">{{translate('Basic Information')}}</a>
                                @if(get_setting('member_present_address_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-3" data-toggle="pill" href="#present_address" role="tab" aria-controls="v-pills-messages" aria-selected="false">{{translate('Present Address')}}</a>
                                @endif
                                @if(get_setting('member_education_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-4" data-toggle="pill" href="#education" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Education')}}</a>
                                @endif
                                @if(get_setting('member_career_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-5" data-toggle="pill" href="#career" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Career')}}</a>
                                @endif
                                @if(get_setting('member_physical_attributes_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-6" data-toggle="pill" href="#physical_attributes" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Physical Attributes')}}</a>
                                @endif
                                @if(get_setting('member_language_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-7" data-toggle="pill" href="#language" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Language')}}</a>
                                @endif
                                @if(get_setting('member_hobbies_and_interests_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-8" data-toggle="pill" href="#hobbies_interest" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Hobbies & Interest')}}</a>
                                @endif
                                @if(get_setting('member_personal_attitude_and_behavior_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-9" data-toggle="pill" href="#attitudes_behavior" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Personal Attitude & Behavior')}}</a>
                                @endif
                                @if(get_setting('member_residency_information_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-10" data-toggle="pill" href="#residency_information" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Residency Information')}}</a>
                                @endif
                                @if(get_setting('member_spiritual_and_social_background_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-11" data-toggle="pill" href="#spiritual_backgrounds" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Spiritual & Social Background')}}</a>
                                @endif
                                @if(get_setting('member_life_style_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-12" data-toggle="pill" href="#lifestyle" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Life Style')}}</a>
                                @endif
                                @if(get_setting('member_astronomic_information_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-13" data-toggle="pill" href="#astronomic_information" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Astronomic Information')}}</a>
                                @endif
                                @if(get_setting('member_permanent_address_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-14" data-toggle="pill" href="#permanent_address" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Permanent Address')}}</a>
                                @endif
                                @if(get_setting('member_family_information_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-15" data-toggle="pill" href="#family_information" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Family Information')}}</a>
                                @endif
                                @if(get_setting('member_partner_expectation_section') == 'on')
                                <a class="nav-link" id="v-pills-tab-16" data-toggle="pill" href="#partner_expectation" role="tab" aria-controls="v-pills-settings" aria-selected="false">{{translate('Partner Expectation')}}</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="introduction" role="tabpanel" aria-labelledby="v-pills-tab-1">
                                    <div class="card">
                                        @include('admin.members.edit.introduction')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="basic_information" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                    <div class="card">
                                        @include('admin.members.edit.basic_information')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="present_address" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                    <div class="card">
                                        @php
                                            $present_address      = \App\Models\Address::where('type','present')->where('user_id',$member->id)->first();
                                            $present_country_id   = $present_address->country_id ?? "";
                                            $present_state_id     = $present_address->state_id ?? "";
                                            $present_city_id      = $present_address->city_id ?? "";
                                            $present_postal_code  = $present_address->postal_code ?? "";
                                        @endphp

                                        @include('admin.members.edit.present_address')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @include('admin.members.edit.education')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="career" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @include('admin.members.edit.career')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="physical_attributes" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @include('admin.members.edit.physical_attributes')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="language" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @include('admin.members.edit.language')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="hobbies_interest" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @include('admin.members.edit.hobbies_interest')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="attitudes_behavior" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @include('admin.members.edit.attitudes_behavior')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="residency_information" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @include('admin.members.edit.residency_information')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="spiritual_backgrounds" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @php
                                            $member_religion_id   = $member->spiritual_backgrounds->religion_id ?? "";
                                            $member_caste_id      = $member->spiritual_backgrounds->caste_id ?? "";
                                            $member_sub_caste_id  = $member->spiritual_backgrounds->sub_caste_id ?? "";
                                        @endphp

                                        @include('admin.members.edit.spiritual_backgrounds')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="lifestyle" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @include('admin.members.edit.lifestyle')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="astronomic_information" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @include('admin.members.edit.astronomic_information')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="permanent_address" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @php
                                            $permanent_address      = \App\Models\Address::where('type','permanent')->where('user_id',$member->id)->first();
                                            $permanent_country_id   = $permanent_address->country_id ?? "";
                                            $permanent_state_id     = $permanent_address->state_id ?? "";
                                            $permanent_city_id      = $permanent_address->city_id ?? "";
                                            $permanent_postal_code  = $permanent_address->postal_code ?? "";
                                        @endphp

                                        @include('admin.members.edit.permanent_address')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="family_information" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @include('admin.members.edit.family_information')
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="partner_expectation" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                    <div class="card">
                                        @php
                                            $partner_religion_id   = $member->partner_expectations->religion_id ?? "";
                                            $partner_caste_id      = $member->partner_expectations->caste_id ?? "";
                                            $partner_sub_caste_id  = $member->partner_expectations->sub_caste_id ?? "";
                                            $partner_country_id    = $member->partner_expectations->preferred_country_id ?? "";
                                            $partner_state_id      = $member->partner_expectations->preferred_state_id ?? "";
                                        @endphp
                                        @include('admin.members.edit.partner_expectation')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.create_edit_modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">

    $(document).ready(function(){
        get_states_by_country_for_present_address();
        get_cities_by_state_for_present_address();
        get_states_by_country_for_permanent_address();
        get_cities_by_state_for_permanent_address();
        get_castes_by_religion_for_member();
        get_sub_castes_by_caste_for_member();
        get_castes_by_religion_for_partner();
        get_sub_castes_by_caste_for_partner();
        get_states_by_country_for_partner();
    });

    // For Present address
    function get_states_by_country_for_present_address(){
        var present_country_id = $('#present_country_id').val();
            $.post('{{ route('states.get_state_by_country') }}',{_token:'{{ csrf_token() }}', country_id:present_country_id}, function(data){
                $('#present_state_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#present_state_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#present_state_id > option").each(function() {
                    if(this.value == '{{$present_state_id}}'){
                        $("#present_state_id").val(this.value).change();
                    }
                });

                AIZ.plugins.bootstrapSelect('refresh');

                get_cities_by_state_for_present_address();
            });
        }

    function get_cities_by_state_for_present_address(){
		var present_state_id = $('#present_state_id').val();
    		$.post('{{ route('cities.get_cities_by_state') }}',{_token:'{{ csrf_token() }}', state_id:present_state_id}, function(data){
    		    $('#present_city_id').html(null);
    		    for (var i = 0; i < data.length; i++) {
    		        $('#present_city_id').append($('<option>', {
    		            value: data[i].id,
    		            text: data[i].name
    		        }));
    		    }
    		    $("#present_city_id > option").each(function() {
    		        if(this.value == '{{$present_city_id}}'){
    		            $("#present_city_id").val(this.value).change();
    		        }
    		    });

    		    AIZ.plugins.bootstrapSelect('refresh');
    		});
    	}

    $('#present_country_id').on('change', function() {
	    get_states_by_country_for_present_address();
	});

    $('#present_state_id').on('change', function() {
	    get_cities_by_state_for_present_address();
	});

    // For permanent address
    function get_states_by_country_for_permanent_address(){
        var permanent_country_id = $('#permanent_country_id').val();
            $.post('{{ route('states.get_state_by_country') }}',{_token:'{{ csrf_token() }}', country_id:permanent_country_id}, function(data){
                $('#permanent_state_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#permanent_state_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#permanent_state_id > option").each(function() {
                    if(this.value == '{{$permanent_state_id}}'){
                        $("#permanent_state_id").val(this.value).change();
                    }
                });

                AIZ.plugins.bootstrapSelect('refresh');

                get_cities_by_state_for_permanent_address();
            });
    }

    function get_cities_by_state_for_permanent_address(){
        var permanent_state_id = $('#permanent_state_id').val();
            $.post('{{ route('cities.get_cities_by_state') }}',{_token:'{{ csrf_token() }}', state_id:permanent_state_id}, function(data){
                $('#permanent_city_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#permanent_city_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#permanent_city_id > option").each(function() {
                    if(this.value == '{{$permanent_city_id}}'){
                        $("#permanent_city_id").val(this.value).change();
                    }
                });

                AIZ.plugins.bootstrapSelect('refresh');
            });
    }

    $('#permanent_country_id').on('change', function() {
        get_states_by_country_for_permanent_address();
    });

    $('#permanent_state_id').on('change', function() {
        get_cities_by_state_for_permanent_address();
    });

    // get castes and subcastes For member
    function get_castes_by_religion_for_member(){
        var member_religion_id = $('#member_religion_id').val();
            $.post('{{ route('castes.get_caste_by_religion') }}',{_token:'{{ csrf_token() }}', religion_id:member_religion_id}, function(data){
                $('#member_caste_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#member_caste_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#member_caste_id > option").each(function() {
                    if(this.value == '{{$member_caste_id}}'){
                        $("#member_caste_id").val(this.value).change();
                    }
                });
                AIZ.plugins.bootstrapSelect('refresh');

                get_sub_castes_by_caste_for_member();
            });
        }

    function get_sub_castes_by_caste_for_member(){
        var member_caste_id = $('#member_caste_id').val();
            $.post('{{ route('sub_castes.get_sub_castes_by_religion') }}',{_token:'{{ csrf_token() }}', caste_id:member_caste_id}, function(data){
                $('#member_sub_caste_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#member_sub_caste_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#member_sub_caste_id > option").each(function() {
                    if(this.value == '{{$member_sub_caste_id}}'){
                        $("#member_sub_caste_id").val(this.value).change();
                    }
                });
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }

    $('#member_religion_id').on('change', function() {
        get_castes_by_religion_for_member();
    });

    $('#member_caste_id').on('change', function() {
        get_sub_castes_by_caste_for_member();
    });

    // get castes and subcastes For partner
    function get_castes_by_religion_for_partner(){
        var partner_religion_id = $('#partner_religion_id').val();
            $.post('{{ route('castes.get_caste_by_religion') }}',{_token:'{{ csrf_token() }}', religion_id:partner_religion_id}, function(data){
                $('#partner_caste_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#partner_caste_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#partner_caste_id > option").each(function() {
                    if(this.value == '{{$partner_caste_id}}'){
                        $("#partner_caste_id").val(this.value).change();
                    }
                });
                AIZ.plugins.bootstrapSelect('refresh');

                get_sub_castes_by_caste_for_partner();
            });
        }

    function get_sub_castes_by_caste_for_partner(){
        var partner_caste_id = $('#partner_caste_id').val();
            $.post('{{ route('sub_castes.get_sub_castes_by_religion') }}',{_token:'{{ csrf_token() }}', caste_id:partner_caste_id}, function(data){
                $('#partner_sub_caste_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#partner_sub_caste_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#partner_sub_caste_id > option").each(function() {
                    if(this.value == '{{$partner_sub_caste_id}}'){
                        $("#partner_sub_caste_id").val(this.value).change();
                    }
                });
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }

    $('#partner_religion_id').on('change', function() {
        get_castes_by_religion_for_partner();
    });

    $('#partner_caste_id').on('change', function() {
        get_sub_castes_by_caste_for_partner();
    });

    // For partner address
    function get_states_by_country_for_partner(){
        var partner_country_id = $('#partner_country_id').val();
            $.post('{{ route('states.get_state_by_country') }}',{_token:'{{ csrf_token() }}', country_id:partner_country_id}, function(data){
                $('#partner_state_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#partner_state_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#partner_state_id > option").each(function() {
                    if(this.value == '{{$partner_state_id}}'){
                        $("#partner_state_id").val(this.value).change();
                    }
                });

                AIZ.plugins.bootstrapSelect('refresh');
            });
    }

    $('#partner_country_id').on('change', function() {
        get_states_by_country_for_partner();
    });

    //  education Add edit , status change
    function education_add_modal(id){
       $.post('{{ route('education.create') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
           $('.create_edit_modal_content').html(data);
           $('.create_edit_modal').modal('show');
       });
    }

    function education_edit_modal(id){
        $.post('{{ route('education.edit') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
            $('.create_edit_modal_content').html(data);
            $('.create_edit_modal').modal('show');
        });
    }

    function update_education_present_status(el) {
        if (el.checked) {
            var status = 1;
        } else {
            var status = 0;
        }
        $.post('{{ route('education.update_education_present_status') }}', {
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


    //  Career Add edit , status change
    function career_add_modal(id){
       $.post('{{ route('career.create') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
           $('.create_edit_modal_content').html(data);
           $('.create_edit_modal').modal('show');
       });
    }

    function career_edit_modal(id){
        $.post('{{ route('career.edit') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
            $('.create_edit_modal_content').html(data);
            $('.create_edit_modal').modal('show');
        });
    }

    function update_career_present_status(el) {
        if (el.checked) {
            var status = 1;
        } else {
            var status = 0;
        }
        $.post('{{ route('career.update_career_present_status') }}', {
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
