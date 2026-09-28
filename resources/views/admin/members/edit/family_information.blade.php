<div class="card-header bg-dark text-white">
    <h5 class="mb-0 h6">{{translate('Family Information')}}</h5>
</div>
<div class="card-body">
    <form action="{{ route('families.update', $member->id) }}" method="POST">
        <input name="_method" type="hidden" value="PATCH">
        @csrf
        <div class="form-group row">
            <div class="col-md-6">
                <label for="father">{{translate('Father')}}</label>
                <input type="text" name="father" value="{{ $member->families->father ?? "" }}" class="form-control" placeholder="{{translate('Father')}}" required>
                @error('father')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="mother">{{translate('Mother')}}</label>
                <input type="text" name="mother" value="{{ $member->families->mother ?? "" }}" placeholder="{{ translate('Mother') }}" class="form-control" required>
                @error('mother')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="sibling">{{translate('Sibling')}}</label>
                <input type="text" name="sibling" value="{{ $member->families->sibling ?? "" }}" class="form-control" placeholder="{{translate('Sibling')}}" required>
                @error('sibling')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
        </div>
    </form>
</div>
