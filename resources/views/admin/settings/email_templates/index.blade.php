@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Email Templates')}}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                                @foreach ($email_templates as $key => $email_template)
                                    <a class="nav-link @if($email_template->id == 1) active @endif" id="v-pills-tab-2" data-toggle="pill" href="#v-pills-{{ $email_template->id }}" role="tab" aria-controls="v-pills-profile" aria-selected="false">{{ translate(ucwords(str_replace('_', ' ', $email_template->identifier)))  }}</a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                @foreach ($email_templates as $key => $email_template)
                                    <div class="tab-pane fade show @if($email_template->id == 1) active @endif" id="v-pills-{{ $email_template->id }}" role="tabpanel" aria-labelledby="v-pills-tab-1">
                                        <form action="{{ route('email-templates.update') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="identifier" value="{{ $email_template->identifier }}">
                                            @if($email_template->identifier != 'password_reset_email')
                                                <div class="form-group row">
                                                    <div class="col-md-2">
                                                        <label class="col-from-label">{{translate('Activation')}}</label>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <label class="aiz-switch aiz-switch-success mb-0">
                                                            <input value="1" name="status" type="checkbox" @if ($email_template->status == 1)
                                                                checked
                                                            @endif>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label">{{translate('Subject')}}</label>
                                                <div class="col-md-10">
                                                    <input type="text" name="subject" value="{{ $email_template->subject }}" class="form-control" placeholder="{{translate('Subject')}}" required>
                                                    @error('subject')
                                                        <small class="form-text text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-2 col-form-label">{{translate('Email Body')}}</label>
                                                <div class="col-md-10">
                                                    <textarea name="body" class="form-control aiz-text-editor" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]' placeholder="Type.." data-min-height="300" required>{{ $email_template->body }}</textarea>
                                                    <small class="form-text text-danger">{{ ('**N.B : Do Not Change The Variables Like [[ ____ ]].**') }}</small>
                                                    @error('body')
                                                        <small class="form-text text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group mb-3 text-right">
                                                <button type="submit" class="btn btn-primary">{{translate('Update Settings')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
