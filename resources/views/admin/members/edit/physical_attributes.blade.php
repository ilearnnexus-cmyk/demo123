<div class="card-header bg-dark text-white">
    <h5 class="mb-0 h6">{{translate('Physical Attributes')}}</h5>
</div>
<div class="card-body">
    <form action="{{ route('physical-attribute.update', $member->id) }}" method="POST">
        <input name="_method" type="hidden" value="PATCH">
        @csrf
        <div class="form-group row">
            <div class="col-md-6">
                <label for="height">{{translate('Height')}}</label>
                <input type="number" name="height" value="{{ $member->physical_attributes->height ?? "" }}" step="any" class="form-control" placeholder="{{translate('Height')}}" required>
                @error('height')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="weight">{{translate('Weight')}}</label>
                <input type="text" name="weight" value="{{ $member->physical_attributes->weight ?? "" }}" placeholder="{{ translate('Weight') }}" step="any" class="form-control" required>
                @error('weight')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="eye_color">{{translate('Eye color')}}</label>
                <input type="text" name="eye_color" value="{{ $member->physical_attributes->eye_color ?? "" }}" class="form-control" placeholder="{{translate('Eye Color')}}" required>
                @error('eye_color')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="hair_color">{{translate('Hair Color')}}</label>
                <input type="text" name="hair_color" value="{{ $member->physical_attributes->hair_color ?? "" }}" placeholder="{{ translate('Hair Color') }}" class="form-control" required>
                @error('hair_color')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="complexion">{{translate('Complexion')}}</label>
                <input type="text" name="complexion" value="{{ $member->physical_attributes->complexion ?? "" }}" class="form-control" placeholder="{{translate('Complexion')}}" required>
                @error('complexion')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="blood_group">{{translate('Blood Group')}}</label>
                <input type="text" name="blood_group" value="{{ $member->physical_attributes->blood_group ?? "" }}" placeholder="{{ translate('Blood Group') }}" class="form-control" required>
                @error('blood_group')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="body_type">{{translate('Body Type')}}</label>
                <input type="text" name="body_type" value="{{ $member->physical_attributes->body_type ?? "" }}" class="form-control" placeholder="{{translate('Body Type')}}" required>
                @error('body_type')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="body_art">{{translate('Body Art')}}</label>
                <input type="text" name="body_art" value="{{ $member->physical_attributes->body_art ?? "" }}" placeholder="{{ translate('Body Art') }}" class="form-control" required>
                @error('body_art')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="disability">{{translate('Disability')}}</label>
                <input type="text" name="disability" value="{{ $member->physical_attributes->disability ?? "" }}" class="form-control" placeholder="{{translate('Disability')}}">
                @error('disability')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
        </div>
    </form>
</div>
