@extends('frontend.layouts.member_panel')
@section('panel_content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Add New Image to Gallery') }}</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('gallery-image.store') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="signinSrEmail">{{translate('Image')}}</label>
                    <div class="col-md-9">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="gallery_image" class="selected-files" required>
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group row text-right">
                    <div class="col-md-11">
                        <button type="submit" class="btn btn-primary">{{translate('Confirm')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
