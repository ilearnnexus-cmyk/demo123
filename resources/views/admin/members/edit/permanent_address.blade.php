<div class="card-header bg-dark text-white">
    <h5 class="mb-0 h6">{{translate('Permanent Address')}}</h5>
</div>
<div class="card-body">
    <form action="{{ route('address.update', $member->id) }}" method="POST">
        <input name="_method" type="hidden" value="PATCH">
        @csrf
        <input type="hidden" name="address_type" value="permanent">
        <div class="form-group row">
            <div class="col-md-6">
                <label for="permanent_country_id">{{translate('Country')}}</label>
                <select class="form-control aiz-selectpicker" name="permanent_country_id" id="permanent_country_id" data-selected="{{ $permanent_country_id }}" data-live-search="true" required>
                    <option value="">{{translate('Select One')}}</option>
                    @foreach ($countries as $country)
                        <option value="{{$country->id}}">{{$country->name}}</option>
                    @endforeach
                </select>
                @error('permanent_country_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="permanent_state_id">{{translate('State')}}</label>
                <select class="form-control aiz-selectpicker" name="permanent_state_id" id="permanent_state_id" data-live-search="true" required>

                </select>
                @error('permanent_state_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="permanent_city_id">{{translate('City')}}</label>
                <select class="form-control aiz-selectpicker" name="permanent_city_id" id="permanent_city_id" data-live-search="true" required>

                </select>
                @error('permanent_city_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="permanent_postal_code">{{translate('Postal Code')}}</label>
                <input type="text" name="permanent_postal_code" value="{{$permanent_postal_code}}" class="form-control" placeholder="{{translate('Postal Code')}}" required>
                @error('permanent_postal_code')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
        </div>
    </form>
</div>
