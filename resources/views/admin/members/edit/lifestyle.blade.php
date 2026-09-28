<div class="card-header bg-dark text-white">
    <h5 class="mb-0 h6">{{translate('Lifestyle')}}</h5>
</div>
<div class="card-body">
    <form action="{{ route('lifestyles.update', $member->id) }}" method="POST">
        <input name="_method" type="hidden" value="PATCH">
        @csrf
        <div class="form-group row">
            <div class="col-md-6">
                <label for="diet">{{translate('Diet')}}</label>
                <input type="text" name="diet" value="{{ $member->lifestyles->diet ?? "" }}" class="form-control" placeholder="{{translate('Diet')}}" required>
                @error('diet')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="drink">{{translate('Drink')}}</label>
                <input type="text" name="drink" value="{{ $member->lifestyles->drink ?? "" }}" placeholder="{{ translate('Drink') }}" class="form-control" required>
                @error('drink')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="smoke">{{translate('Smoke')}}</label>
                <input type="text" name="smoke" value="{{ $member->lifestyles->smoke ?? "" }}" class="form-control" placeholder="{{translate('Smoke')}}" required>
                @error('diet')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="living_with">{{translate('Living With')}}</label>
                <input type="text" name="living_with" value="{{ $member->lifestyles->living_with ?? "" }}" placeholder="{{ translate('Living With') }}" class="form-control" required>
                @error('living_with')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
        </div>
    </form>
</div>
