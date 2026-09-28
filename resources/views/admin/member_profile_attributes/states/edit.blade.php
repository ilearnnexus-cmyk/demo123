@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Edit State Info')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('states.update', $state->id) }}" method="POST">
                        <input name="_method" type="hidden" value="PATCH">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">{{translate('Country')}}</label>
                            <select class="form-control aiz-selectpicker" data-live-search="true" name="country_id" required>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}" @if($country->id == $state->country_id) selected @endif>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('religion')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">{{translate('State Name')}}</label>
                            <input type="text" id="name" name="name" value="{{$state->name}}" class="form-control"
                                   required>
                           @error('name')
                               <small class="form-text text-danger">{{ $message }}</small>
                           @enderror
                        </div>

                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
