<div class="card-header bg-dark text-white">
    <h5 class="mb-0 h6">{{translate('Astronomic Information')}}</h5>
</div>
<div class="card-body">
    <form action="{{ route('astrologies.update', $member->id) }}" method="POST">
        <input name="_method" type="hidden" value="PATCH">
        @csrf
        <div class="form-group row">
            <div class="col-md-6">
                <label for="sun_sign">{{translate('Sun Sign')}}</label>
                <input type="text" name="sun_sign" value="{{ $member->astrologies->sun_sign ?? "" }}" class="form-control" placeholder="{{translate('Sun Sign')}}" required>
                @error('sun_sign')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="moon_sign">{{translate('Moon Sign')}}</label>
                <input type="text" name="moon_sign" value="{{ $member->astrologies->moon_sign ?? "" }}" placeholder="{{ translate('Moon Sign') }}" class="form-control" required>
                @error('moon_sign')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="time_of_birth">{{translate('Time Of Birth')}}</label>
                <input type="text" name="time_of_birth" value="{{ $member->astrologies->time_of_birth ?? "" }}" class="form-control" placeholder="{{translate('Time Of Birth')}}" required>
                @error('time_of_birth')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="city_of_birth">{{translate('City Of Birth')}}</label>
                <input type="text" name="city_of_birth" value="{{ $member->astrologies->city_of_birth ?? "" }}" placeholder="{{ translate('City Of Birth') }}" class="form-control" required>
                @error('moon_sign')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
        </div>
    </form>
</div>
