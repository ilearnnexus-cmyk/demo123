<div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl z-1035">
    <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
    <div class="card collapse-sidebar c-scrollbar-light shadow-none">
        <div class="card-header pr-1 pl-3">
            <h5 class="mb-0 h6">{{ translate('ADVANCED SEARCH') }}</h5>
            <button class="btn btn-sm p-2 d-xl-none filter-sidebar-thumb" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" type="button">
                <i class="las la-times la-2x"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="pb-4">
                <form action="{{ route('member.listing') }}" method="get">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="age_from">{{ translate('Age  From') }}</label>
                                <input type="number" name="age_from" value="{{ $age_from }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="age_to">{{ translate('To') }}</label>
                                <input type="number" name="age_to" value="{{ $age_to }}" class="form-control">
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ translate('Member ID') }}</label>
                                <input type="text" name="member_code" value="{{ $member_code }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ translate('Maritial Status') }}</label>
                                @php $marital_statuses = \App\Models\MaritalStatus::all(); @endphp
                                <select class="form-control aiz-selectpicker" name="marital_status" data-live-search="true">
                                    <option value="">{{translate('Select One')}}</option>
                                    @foreach ($marital_statuses as $marital_status)
                                        <option value="{{$marital_status->id}}" @if($matital_status == $marital_status->id) selected @endif >{{$marital_status->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ translate('Religion') }}</label>
                                @php $religions = \App\Models\Religion::all(); @endphp
                                <select name="religion_id" id="religion_id" class="form-control aiz-selectpicker"  data-live-search="true" >
                                    <option value="">{{translate('Choose One')}}</option>
                                    @foreach ($religions as $religion)
                                        <option value="{{ $religion->id }}" @if($religion->id == $religion_id) selected @endif> {{ $religion->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ translate('Caste') }}</label>
                                <select name="caste_id" id="caste_id" class="form-control aiz-selectpicker" data-live-search="true" >
                                    <option value="">{{translate('Select One')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ translate('Sub Caste') }}</label>
                                <select name="sub_caste_id" id="sub_caste_id" class="form-control aiz-selectpicker" data-live-search="true">
                                    <option value="">{{translate('Select One')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ translate('Mother  Tongue') }}</label>
                                @php $mother_tongues = \App\Models\MemberLanguage::all(); @endphp
                                <select name="mother_tongue" class="form-control aiz-selectpicker" data-live-search="true" >
                                    <option value="">{{translate('Select One')}}</option>
                                    @foreach ($mother_tongues as $mother_tongue_select)
                                        <option value="{{$mother_tongue_select->id}}" @if($mother_tongue_select->id == $mother_tongue) selected @endif> {{ $mother_tongue_select->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                        <div class="form-grohp mb-3">
                                <label class="form-label" for="name">{{ translate('Profession') }}</label>
                                <input type="text" name="profession" value="{{ $profession }}" class="form-control" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ translate('Country') }}</label>
                                @php $countries = \App\Models\Country::where('status',1)->get(); @endphp
                                <select name="country_id" id="country_id" class="form-control aiz-selectpicker" data-live-search="true" >
                                    <option value="">{{translate('Select One')}}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" @if($country->id == $country_id) selected @endif >{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ translate('State') }}</label>
                                <select name="state_id" id="state_id" class="form-control aiz-selectpicker" data-live-search="true" >

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ translate('City') }}</label>
                                <select name="city_id" id="city_id" class="form-control aiz-selectpicker" data-live-search="true" >

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ translate('Min Height') }}</label>
                                <input type="number" name="min_height" value="{{ $min_height }}" class="form-control" min="0" step="0.01"  >
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">{{ translate('Max Height') }}</label>
                                <input type="number" name="max_height" value="{{ $max_height }}" class="form-control" min="0" step="0.01"   >
                          </div>
                        </div>
                    </div>
                    <h6 class="separator text-left mb-3 fs-12 text-uppercase text-secondary">
                        <span class="bg-white pr-3">{{ translate('Member Type') }}</span>
                    </h6>
                    <div class="aiz-radio-list">
                        <label class="aiz-radio">
                            <input type="radio" name="member_type" value="2" onchange="applyFilter()" @if($member_type == 2) checked @endif > {{ translate('Premium Member') }}
                            <span class="aiz-rounded-check"></span>
                        </label>
                        <label class="aiz-radio">
                            <input type="radio" name="member_type" value="1" onchange="applyFilter()"  @if($member_type == 1) checked @endif > {{ translate('Free member') }}
                            <span class="aiz-rounded-check"></span>
                        </label>
                        <label class="aiz-radio">
                            <input type="radio" name="member_type" value="0" @if($member_type == 0) checked @endif> {{ translate('All Member') }}
                            <span class="aiz-rounded-check"></span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-block btn-primary mt-4">{{ translate('Search') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
