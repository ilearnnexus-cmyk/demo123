@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
      <div class="card">
          <div class="card-header">
              <h5 class="mb-0 h6">{{translate('Edit Happy Story')}}</h5>
          </div>
          <div class="card-body">
            <form action="{{ route('happy-story.update', $happy_story->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="form-group ">
                    <label class="form-label" for="name">{{translate('Story Title')}} <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ $happy_story->title }}"  placeholder="{{translate('Title')}}" required>
                    @error('title')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="from-label" for="name">{{translate('Story Details')}} <span class="text-danger">*</span></label>
                    <textarea name="details" class="aiz-text-editor form-control" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]' placeholder="Type.." data-min-height="200">{{ $happy_story->details }}</textarea>
                    @error('details')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="from-label" for="name">{{translate('Partner Name')}} <span class="text-danger">*</span></label>
                    <input type="text" name="partner_name" value="{{ $happy_story->partner_name }}" class="form-control"  placeholder="{{translate('Partner Name')}}" required>
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
                        <input type="hidden" name="photos" value="{{ $happy_story->photos }}" class="selected-files" required>
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
        							<option value="youtube" @if($happy_story->video_provider == 'youtube') selected @endif>{{translate('Youtube')}}</option>
        							<option value="dailymotion" @if($happy_story->video_provider == 'dailymotion') selected @endif>{{translate('Dailymotion')}}</option>
        							<option value="vimeo" @if($happy_story->video_provider == 'vimeo') selected @endif>{{translate('Vimeo')}}</option>
        						</select>
        				</div>
        				<div class="form-group ">
        					<label class="from-label">{{translate('Video Link')}}</label>
        						<input type="text" name="video_link" value="{{ $happy_story->video_link }}" class="form-control" placeholder="{{ translate('Video Link') }}">
                    <small class="text-muted">{{translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.")}}</small>
        				</div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
              </form>
          </div>
      </div>
    </div>
</div>
@endsection
