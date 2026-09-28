<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Lifestyle')}}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('lifestyles.update', $member->id) }}" method="POST">
            <input name="_method" type="hidden" value="PATCH">
            @csrf
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="diet">{{translate('Diet')}}</label>
                    @php $user_diet = !empty($member->lifestyles->diet) ? $member->lifestyles->diet : ""; @endphp
                    <select class="form-control aiz-selectpicker" name="diet" required>
                        <option value="yes" @if($user_diet ==  'yes') selected @endif >{{translate('Yes')}}</option>
                        <option value="no" @if($user_diet ==  'no') selected @endif >{{translate('No')}}</option>
                        @error('diet')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="drink">{{translate('Drink')}}</label>
                    @php $user_drink = !empty($member->lifestyles->drink) ? $member->lifestyles->drink : ""; @endphp
                    <select class="form-control aiz-selectpicker" name="drink" required>
                        <option value="yes" @if($user_drink ==  'yes') selected @endif >{{translate('Yes')}}</option>
                        <option value="no" @if($user_drink ==  'no') selected @endif >{{translate('No')}}</option>
                        @error('drink')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="smoke">{{translate('Smoke')}}</label>
                    @php $user_smoke = !empty($member->lifestyles->smoke) ? $member->lifestyles->smoke : ""; @endphp
                    <select class="form-control aiz-selectpicker" name="smoke" required>
                        <option value="yes" @if($user_smoke ==  'yes') selected @endif >{{translate('Yes')}}</option>
                        <option value="no" @if($user_smoke ==  'no') selected @endif >{{translate('No')}}</option>
                        @error('smoke')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </select>
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
</div>
