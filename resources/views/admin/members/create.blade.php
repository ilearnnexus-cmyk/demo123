@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Add New Member')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{translate('First Name')}}<span class="text-danger"> *</span></label>
                            <div class="col-md-9">
                                <input type="text" name="first_name" class="form-control" placeholder="{{translate('First Name')}}" required>
                                @error('first_name')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{translate('Last Name')}}<span class="text-danger"> *</span></label>
                            <div class="col-md-9">
                                <input type="text" name="last_name" class="form-control" placeholder="{{translate('Last Name')}}" required>
                                @error('last_name')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{translate('Email')}}</label>
                            <div class="col-md-9">
                                <input type="email" name="email" class="form-control" placeholder="{{translate('Email')}}">
                                @error('email')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{translate('Gender')}}<span class="text-danger"> *</span></label>
                            <div class="col-md-9">
                                <select class="form-control aiz-selectpicker" name="gender" required>
                                    <option value="1">{{translate('Male')}}</option>
                                    <option value="2">{{translate('Female')}}</option>
                                </select>
                                @error('gender')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{translate('Date Of Birth')}}<span class="text-danger"> *</span></label>
                            <div class="col-md-9">
                                <input type="text" class="aiz-date-range form-control" name="date_of_birth"  placeholder="Select Date" data-single="true" data-show-dropdown="true">
                            </div>
                            @error('date_of_birth')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        @if(addon_activation('otp_system'))
                          <div class="form-group row">
                              <label class="col-md-2 col-form-label">{{translate('Phone Number')}}</label>
                              <div class="col-md-9">
                                <input type="tel" id="phone-code" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="{{ translate('Phone')}}" >
                                <input type="hidden" name="country_code" value="">
                                  @error('phone')
                                      <small class="form-text text-danger">{{ $message }}</small>
                                  @enderror
                              </div>
                          </div>
                        @endif
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{translate('On Behalf')}}<span class="text-danger"> *</span></label>
                            <div class="col-md-9">
                                @php $on_behalves = \App\Models\OnBehalf::all(); @endphp
                                <select class="form-control aiz-selectpicker" name="on_behalf" required>
                                    @foreach ($on_behalves as $on_behalf)
                                        <option value="{{$on_behalf->id}}">{{$on_behalf->name}}</option>
                                    @endforeach
                                </select>
                                @error('on_behalf')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{translate('Package')}}<span class="text-danger"> *</span></label>
                            <div class="col-md-9">
                                @php $packages = \App\Models\Package::where('active', 1)->get(); @endphp
                                <select class="form-control aiz-selectpicker" name="package" required>
                                    @foreach ($packages as $package)
                                        <option value="{{$package->id}}">{{$package->name}}</option>
                                    @endforeach
                                </select>
                                @error('package')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">{{translate('photo')}} <small>(800x800)</small></label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="photo" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{translate('Password')}}<span class="text-danger"> *</span></label>
                            <div class="col-md-9">
                                <input type="password" name="password" id="new_password" class="form-control" placeholder="{{translate('Password')}}" required>
                                @error('password')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{translate('Confirm Password')}} <span class="text-danger"> *</span></label>
                            <div class="col-md-9">
                                <input type="password" class="form-control" name="confirm_password" onkeyup="checkPasswordValidation(123)" id="confirm_password" placeholder="{{translate('Confirm Password')}}" required>
                                <small id="confirm_password_help" class="form-text text-muted" style="color: red; display: none;">{{ translate('Mismatch Password.') }}</small>
                                @error('confirm_password')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">{{translate('Member Verified')}}</label>
                            <div class="col-md-9 mt-2">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="member_verification" type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row text-right">
                            <div class="col-md-11">
                                <button type="submit" class="btn btn-primary" id="passSaveBtn" disabled>{{translate('Add Member')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

<script type="text/javascript">

	function checkPasswordValidation(confirm_password) {
        var new_password = $('#new_password').val();
        var confirm_password = $('#confirm_password').val();
        $('#confirm_password_help').show();
        if(new_password === confirm_password) {
            $('#confirm_password_help').text('Password Matched');
            $('#passSaveBtn').prop("disabled", false);
        }else {
            $('#confirm_password_help').text('Mismatched Password');
            $('#passSaveBtn').prop("disabled", true);
        }
    }

    var isPhoneShown = true,
        countryData = window.intlTelInputGlobals.getCountryData(),
        input = document.querySelector("#phone-code");

    for (var i = 0; i < countryData.length; i++) {
        var country = countryData[i];
        if(country.iso2 == 'bd'){
            country.dialCode = '88';
        }
    }

    var iti = intlTelInput(input, {
        separateDialCode: true,
        utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
        onlyCountries: @php echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray()) @endphp,
        customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
            if(selectedCountryData.iso2 == 'bd'){
                return "01xxxxxxxxx";
            }
            return selectedCountryPlaceholder;
        }
    });

    var country = iti.getSelectedCountryData();
    $('input[name=country_code]').val(country.dialCode);

    input.addEventListener("countrychange", function(e) {
        // var currentMask = e.currentTarget.placeholder;

        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);

    });

</script>

@endsection
