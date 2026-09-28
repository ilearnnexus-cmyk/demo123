<div class="card-header bg-dark text-white">
    <h5 class="mb-0 h6">{{translate('Language')}}</h5>
</div>
<div class="card-body">
    <form action="{{ route('member.language_info_update', $member->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="diet">{{translate('Mother Tongue')}}</label>
            <select class="form-control aiz-selectpicker" name="mothere_tongue" data-selected="{{ $member->member->mothere_tongue }}" data-live-search="true">
                <option value="">{{translate('Select One')}}</option>
                @foreach ($languages as $language)
                    <option value="{{$language->id}}"> {{ $language->name }} </option>
                @endforeach
            </select>
            @error('mothere_tongue')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="drink">{{translate('Known Languages')}}</label>
            @php $known_languages = !empty($member->member->known_languages) ? json_decode($member->member->known_languages) : [] ; @endphp
            <select class="form-control aiz-selectpicker" name="known_languages[]" data-live-search="true" multiple>
                <option value="">{{translate('Select')}}</option>
                @foreach ($languages as $language)
                    <option value="{{$language->id}}" @if(in_array($language->id, $known_languages)) selected @endif >{{ $language->name }} </option>
                @endforeach
            </select>
            @error('known_languages')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
        </div>
    </form>
</div>
