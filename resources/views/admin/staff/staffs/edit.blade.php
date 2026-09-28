@extends('admin.layouts.app')

@section('content')

<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Staff Information')}}</h5>
        </div>

        <form action="{{ route('staffs.update', $staff->id) }}" method="POST">
            <input name="_method" type="hidden" value="PATCH">
        	@csrf
            <div class="card-body">
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="first_name">{{translate('First Name')}}</label>
                  <div class="col-sm-9">
                      <input type="text" name="first_name" value="{{ $staff->user->first_name }}" placeholder="{{translate('First Name')}}" id="first_name" class="form-control" required>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="last_name">{{translate('Last Name')}}</label>
                  <div class="col-sm-9">
                      <input type="text" name="last_name" value="{{ $staff->user->last_name }}" placeholder="{{translate('Last Name')}}" id="last_name" class="form-control" required>
                  </div>
              </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="email" value="{{ $staff->user->email }}" placeholder="{{translate('Email')}}" id="email" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="mobile">{{translate('Phone')}}</label>
                    <div class="col-sm-9">
                        <input type="text" name="mobile" value="{{ $staff->user->phone }}" placeholder="{{translate('Phone')}}" id="mobile" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" name="password" placeholder="{{translate('Password')}}" id="password" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="password">{{translate('Role')}}</label>
                    <div class="col-sm-9">
                        <select name="role_id" required class="form-control aiz-selectpicker">
                            @foreach($roles as $role)
                                <option value="{{$role->id}}" @if($role->id == $staff->role_id) selected @endif>{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
