<div class="card-header bg-dark text-white">
    <h5 class="mb-0 h6">{{translate('Residency Information')}}</h5>
</div>
<div class="card-body">
    <form action="{{ route('recidencies.update', $member->id) }}" method="POST">
        <input name="_method" type="hidden" value="PATCH">
        @csrf
        @php
            $countries = \App\Models\Country::where('status',1)->get();
            $birth_country_id = $member->recidency->birth_country_id ?? "";
            $recidency_country_id = $member->recidency->recidency_country_id ?? "";
            $growup_country_id = $member->recidency->growup_country_id ?? "";
        @endphp
        <div class="form-group row">
            <div class="col-md-6">
                <label for="birth_country_id">{{translate('Birth Country')}}</label>
                <select class="form-control aiz-selectpicker" name="birth_country_id" data-selected="{{ $birth_country_id }}" data-live-search="true">
                    @foreach ($countries as $country)
                        <option value="{{$country->id}}">{{$country->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="recidency_country_id">{{translate('Residency Country')}}</label>
                <select class="form-control aiz-selectpicker" name="recidency_country_id" data-selected="{{ $recidency_country_id }}" data-live-search="true">
                    @foreach ($countries as $country)
                        <option value="{{$country->id}}">{{$country->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="growup_country_id">{{translate('Grow Up Country')}}</label>
                <select class="form-control aiz-selectpicker" name="growup_country_id" data-selected="{{ $growup_country_id }}" data-live-search="true">
                    @foreach ($countries as $country)
                        <option value="{{$country->id}}">{{$country->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="immigration_status">{{translate('Immigration Status')}}</label>
                <input type="text" name="immigration_status" value="{{ $member->recidency->immigration_status ?? "" }}" placeholder="{{ translate('Immigration Status') }}" class="form-control">
                @error('immigration_status')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
        </div>
    </form>
</div>
