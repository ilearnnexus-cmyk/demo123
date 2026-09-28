@extends('admin.layouts.app')

@section('content')

<div class="col-lg-6  mx-auto">
      <div class="card">
          <div class="card-header">
              <h5 class="mb-0 h6">{{translate('Profile')}}</h5>
          </div>
          <div class="card-body">
              <form action="{{ route('profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                  <input name="_method" type="hidden" value="PATCH">
                  @csrf
                  <div class="form-group row">
                      <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                      <div class="col-sm-5">
                          <input type="text" class="form-control" placeholder="{{translate('First Name')}}" name="first_name" value="{{ Auth::user()->first_name }}" required>
                      </div>
                      <div class="col-sm-4">
                          <input type="text" class="form-control" placeholder="{{translate('Last Name')}}" name="last_name" value="{{ Auth::user()->last_name }}" required>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-3 col-from-label" for="name">{{translate('Email')}}</label>
                      <div class="col-sm-9">
                          <input type="email" class="form-control" placeholder="{{translate('Email')}}" name="email" value="{{ Auth::user()->email }}">
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-3 col-from-label" for="new_password">{{translate('New Password')}}</label>
                      <div class="col-sm-9">
                          <input type="password" class="form-control" placeholder="{{translate('New Password')}}" name="new_password">
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-3 col-from-label" for="confirm_password">{{translate('Confirm Password')}}</label>
                      <div class="col-sm-9">
                          <input type="password" class="form-control" placeholder="{{translate('Confirm Password')}}" name="confirm_password">
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Image')}} <small>(90x90)</small></label>
                      <div class="col-md-9">
                          <div class="input-group" data-toggle="aizuploader" data-type="image">
                              <div class="input-group-prepend">
                                  <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                              </div>
                              <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                              <input type="hidden" name="photo" class="selected-files" value="{{ Auth::user()->photo }}">
                          </div>
                          <div class="file-preview box sm">
                          </div>
                      </div>
                  </div>
                  <div class="form-group mb-0 text-right">
                      <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                  </div>
              </form>
          </div>
      </div>
  </div>

@endsection
