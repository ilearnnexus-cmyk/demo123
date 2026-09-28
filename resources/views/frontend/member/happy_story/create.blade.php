<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Add Your Story')}}</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('happy-story.store') }}" method="POST">
          @csrf
          <div class="form-group ">
              <label class="form-label" for="name">{{translate('Story Title')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="title"  placeholder="{{translate('Title')}}" required>
              @error('title')
                  <small class="form-text text-danger">{{ $message }}</small>
              @enderror
          </div>
          <div class="form-group">
              <label class="from-label" for="name">{{translate('Story Details')}} <span class="text-danger">*</span></label>
              <textarea name="details" class="aiz-text-editor form-control" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]' placeholder="Type.." data-min-height="200"></textarea>
              @error('details')
                  <small class="form-text text-danger">{{ $message }}</small>
              @enderror
          </div>
          <div class="form-group">
              <label class="from-label" for="name">{{translate('Partner Name')}} <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="partner_name"  placeholder="{{translate('Partner Name')}}" required>
              @error('partner_name')
                  <small class="form-text text-danger">{{ $message }}</small>
              @enderror
          </div>
          <div class="form-group">
              <label class="form-label" for="signinSrEmail">{{translate('Photos')}} <span class="text-danger">*</span></label>
              <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                  <div class="input-group-prepend">
                      <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                  </div>
                  <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                  <input type="hidden" name="photos" class="selected-files" required>
              </div>
              <div class="file-preview box sm">
              </div>
              @error('photos')
                  <small class="form-text text-danger">{{ $message }}</small>
              @enderror
          </div>
          <div class="form-group ">
    					<label class="from-label">{{translate('Video Provider')}}</label>
  						<select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
  							<option value="youtube">{{translate('Youtube')}}</option>
  							<option value="dailymotion">{{translate('Dailymotion')}}</option>
  							<option value="vimeo">{{translate('Vimeo')}}</option>
  						</select>
  				</div>
  				<div class="form-group ">
  					<label class="from-label">{{translate('Video Link')}}</label>
  						<input type="text" class="form-control" name="video_link" placeholder="{{ translate('Video Link') }}">
              <small class="text-muted">{{translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.")}}</small>
  				</div>
          <div class="form-group mb-0 text-right">
              <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
          </div>
        </form>
    </div>
</div>
