@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Member Profile Sections Configuration')}}</h5>
                </div>
                <div class="card-body offset-lg-3">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_present_address_section">
                                <input type="checkbox" name="member_present_address_section" class="custom-control-input" id="present_address" @if( get_setting('member_present_address_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="present_address">{{translate('Present Address')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_education_section">
                                <input type="checkbox" name="member_education_section" class="custom-control-input" id="education" @if( get_setting('member_education_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="education">{{translate('Education')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_career_section">
                                <input type="checkbox" name="member_career_section" class="custom-control-input" id="career" @if( get_setting('member_career_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="career">{{translate('Career')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_physical_attributes_section">
                                <input type="checkbox" name="member_physical_attributes_section" class="custom-control-input" id="physical_attributes" @if( get_setting('member_physical_attributes_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="physical_attributes">{{translate('Physical Attributes')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_language_section">
                                <input type="checkbox" name="member_language_section" class="custom-control-input" id="language" @if( get_setting('member_language_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="language">{{translate('Language')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_hobbies_and_interests_section">
                                <input type="checkbox" name="member_hobbies_and_interests_section" class="custom-control-input" id="hobbies_and_interests" @if( get_setting('member_hobbies_and_interests_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="hobbies_and_interests">{{translate('Hobbies And Interests')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_personal_attitude_and_behavior_section">
                                <input type="checkbox" name="member_personal_attitude_and_behavior_section" class="custom-control-input" id="personal_attitude_and_behavior" @if( get_setting('member_personal_attitude_and_behavior_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="personal_attitude_and_behavior">{{translate('Personal Attitude And Behavior')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_residency_information_section">
                                <input type="checkbox" name="member_residency_information_section" class="custom-control-input" id="residency_information" @if( get_setting('member_residency_information_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="residency_information">{{translate('Residency Information')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_spiritual_and_social_background_section">
                                <input type="checkbox" name="member_spiritual_and_social_background_section" class="custom-control-input" id="spiritual_and_social_background" @if( get_setting('member_spiritual_and_social_background_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="spiritual_and_social_background">{{translate('Spiritual And Social Background')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_life_style_section">
                                <input type="checkbox" name="member_life_style_section" class="custom-control-input" id="life_style" @if( get_setting('member_life_style_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="life_style">{{translate('Life Style')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_astronomic_information_section">
                                <input type="checkbox" name="member_astronomic_information_section" class="custom-control-input" id="astronomic_information" @if( get_setting('member_astronomic_information_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="astronomic_information">{{translate('Astronomic Information')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_permanent_address_section">
                                <input type="checkbox" name="member_permanent_address_section" class="custom-control-input" id="permanent_address" @if( get_setting('member_permanent_address_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="permanent_address">{{translate('Permanent Address')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_family_information_section">
                                <input type="checkbox" name="member_family_information_section" class="custom-control-input" id="family_information" @if( get_setting('member_family_information_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="family_information">{{translate('Family Information')}}</label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="types[]" value="member_partner_expectation_section">
                                <input type="checkbox" name="member_partner_expectation_section" class="custom-control-input" id="partner_expectation" @if( get_setting('member_partner_expectation_section') == 'on') Checked @endif >
                                <label class="custom-control-label" for="partner_expectation">{{translate('Partner Expectation')}}</label>
                            </div>
                        </div>

                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{translate('Update Settings')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
