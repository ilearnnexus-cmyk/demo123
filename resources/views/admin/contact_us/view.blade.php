@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Contact Us Query') }}</h5>
                </div>
                <div class="card-body">
                    <div class="div">
                        <h5><b> Subject :</b> {{ $contactUs->subject }}</h5>
                        <p> <b> Description :</b> {{ $contactUs->description }}</p>
                    </div>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="text-danger my-2 font-weight-bold">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form id="add_form" class="form-horizontal" action="{{ route('contact-us.update', $contactUs->id) }}"
                        method="POST">
                        @csrf
                        @method('put')
                        <input type="hidden" name="status" value="1">
                        <div class="mb-3">
                            <label class="form-label text-primary-grad"> {{ translate('Reply') }} <span
                                    class="text-danger">*</span> </label>
                            <textarea class="form-control" rows="8" placeholder=" {{ translate('Write your reply here') }}" name="reply"
                                required style="resize: none;">{{ $contactUs->reply ? $contactUs->reply : '' }}</textarea>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">
                                {{ translate('Send') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
