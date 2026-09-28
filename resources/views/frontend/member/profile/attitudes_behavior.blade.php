<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Personal Attitude & Behavior')}}</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('attitudes.update', $member->id) }}" method="POST">
          <input name="_method" type="hidden" value="PATCH">
          @csrf
          <div class="form-group row">
              <div class="col-md-6">
                  <label for="affection">{{translate('Affection')}}</label>
                  <input type="text" name="affection" value="{{ $member->attitude->affection ?? "" }}" class="form-control" placeholder="{{translate('Affection')}}">
                  @error('affection')
                      <small class="form-text text-danger">{{ $message }}</small>
                  @enderror
              </div>
              <div class="col-md-6">
                  <label for="humor">{{translate('Humor')}}</label>
                  <input type="text" name="humor" value="{{ $member->attitude->humor ?? "" }}" placeholder="{{ translate('Humor') }}" class="form-control">
                  @error('humor')
                      <small class="form-text text-danger">{{ $message }}</small>
                  @enderror
              </div>
          </div>
          <div class="form-group row">
              <div class="col-md-6">
                  <label for="political_views">{{translate('Political Views')}}</label>
                  <input type="text" name="political_views" value="{{ $member->attitude->political_views ?? "" }}" class="form-control" placeholder="{{translate('Political Views')}}">
              </div>
              <div class="col-md-6">
                  <label for="religious_service">{{translate('Religious Service')}}</label>
                  <input type="text" name="religious_service" value="{{ $member->attitude->religious_service ?? "" }}" placeholder="{{ translate('Religious Service') }}" class="form-control">
              </div>
          </div>

          <div class="text-right">
              <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
          </div>
      </form>
    </div>
</div>
