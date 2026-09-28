<div class="card-header bg-dark text-white">
    <h5 class="mb-0 h6">{{translate('Present Address')}}</h5>
</div>
<div class="card-body">
    <form action="{{ route('address.update', $member->id) }}" method="POST">
        <input name="_method" type="hidden" value="PATCH">
        @csrf
        <input type="hidden" name="address_type" value="present">
        <div class="form-group row">
            <div class="col-md-6">
                <label for="present_country_id">{{translate('Country')}}</label>
                <select class="form-control aiz-selectpicker" name="present_country_id" id="present_country_id" data-selected="{{ $present_country_id }}" data-live-search="true" required>
                    <option value="">{{translate('Select One')}}</option>
                    @foreach ($countries as $country)
                        <option value="{{$country->id}}">{{$country->name}}</option>
                    @endforeach
                </select>
                @error('present_country_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="present_state_id">{{translate('State')}}</label>
                <select class="form-control aiz-selectpicker" name="present_state_id" id="present_state_id" data-live-search="true" required>

                </select>
                @error('present_state_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="present_city_id">{{translate('City')}}</label>
                <select class="form-control aiz-selectpicker" name="present_city_id" id="present_city_id" data-live-search="true" required>

                </select>
                @error('present_city_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="present_postal_code">{{translate('Postal Code')}}</label>
                <input type="text" name="present_postal_code" value="{{$present_postal_code}}" class="form-control" placeholder="{{translate('Postal Code')}}" required>
                @error('present_postal_code')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>


        <div class="text-right">
            <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
        </div>
    </form>
</div>
