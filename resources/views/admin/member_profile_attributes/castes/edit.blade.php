@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Edit Caste Info')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('castes.update', $caste->id) }}" method="POST" >
                        <input name="_method" type="hidden" value="PATCH">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">{{translate('Religion')}}</label>
                            <select class="form-control aiz-selectpicker" data-live-search="true" name="religion" required>
                                @foreach($religions as $religion)
                                    <option value="{{$religion->id}}" @if($religion->id == $caste->religion_id) selected @endif>{{ $religion->name }}</option>
                                @endforeach
                            </select>
                            @error('religion')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">{{translate('Caste Name')}}</label>
                            <input type="text" id="name" name="name" value="{{$caste->name}}" class="form-control"
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
