@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Google Login Credential')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('third_party_settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="setting_type" value="google_login">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="col-from-label">{{translate('Activation')}}</label>
                        </div>
                        <div class="col-md-8">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="google_login_activation" type="checkbox" @if (get_setting('google_login_activation') == 1)
                                    checked
                                @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="GOOGLE_CLIENT_ID">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('Client ID')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="GOOGLE_CLIENT_ID" value="{{  env('GOOGLE_CLIENT_ID') }}" placeholder="{{ translate('Google Client ID') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="GOOGLE_CLIENT_SECRET">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('Client Secret')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="GOOGLE_CLIENT_SECRET" value="{{  env('GOOGLE_CLIENT_SECRET') }}" placeholder="{{ translate('Google Client Secret') }}" required>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Facebook Login Credential')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('third_party_settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="setting_type" value="facebook_login">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="col-from-label">{{translate('Activation')}}</label>
                        </div>
                        <div class="col-md-8">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="facebook_login_activation" type="checkbox" @if (get_setting('facebook_login_activation') == 1)
                                    checked
                                @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="FACEBOOK_CLIENT_ID">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('App ID')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="FACEBOOK_CLIENT_ID" value="{{ env('FACEBOOK_CLIENT_ID') }}" placeholder="{{ translate('Facebook Client ID') }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="FACEBOOK_CLIENT_SECRET">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('App Secret')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="FACEBOOK_CLIENT_SECRET" value="{{ env('FACEBOOK_CLIENT_SECRET') }}" placeholder="{{ translate('Facebook Client Secret') }}" required>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Twitter Login Credential')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('third_party_settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="setting_type" value="twitter_login">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="col-from-label">{{translate('Activation')}}</label>
                        </div>
                        <div class="col-md-8">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="twitter_login_activation" type="checkbox" @if (get_setting('twitter_login_activation') == 1)
                                    checked
                                @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="TWITTER_CLIENT_ID">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('Client ID')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="TWITTER_CLIENT_ID" value="{{ env('TWITTER_CLIENT_ID') }}" placeholder="{{ translate('Twitter Client ID') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="TWITTER_CLIENT_SECRET">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{translate('Client Secret')}}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="TWITTER_CLIENT_SECRET" value="{{ env('TWITTER_CLIENT_SECRET') }}" placeholder="{{ translate('Twitter Client Secret') }}" required>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Apple Login Credential') }}</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('third_party_settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="types[]" value="SIGN_IN_WITH_APPLE_LOGIN">
                    <input type="hidden" name="SIGN_IN_WITH_APPLE_LOGIN" value="{{ url('/users/login') }}"
                        required>
                    <input type="hidden" name="types[]" value="SIGN_IN_WITH_APPLE_REDIRECT">
                    <input type="hidden" name="setting_type" value="apple_login">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="col-from-label">{{translate('Activation')}}</label>
                        </div>
                        <div class="col-md-8">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="apple_login_activation" type="checkbox" @if (get_setting('apple_login_activation') == 1)
                                    checked
                                @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="SIGN_IN_WITH_APPLE_REDIRECT">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{ translate('Callback URL') }}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="SIGN_IN_WITH_APPLE_REDIRECT" value="{{ url('/apple-callback') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="SIGN_IN_WITH_APPLE_CLIENT_ID">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{ translate('Client ID') }}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="SIGN_IN_WITH_APPLE_CLIENT_ID"
                                value="{{ env('SIGN_IN_WITH_APPLE_CLIENT_ID') }}"
                                placeholder="{{ translate('Apple Client ID') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="SIGN_IN_WITH_APPLE_CLIENT_SECRET">
                        <div class="col-lg-3">
                            <label class="col-from-label">{{ translate('Client Secret') }}</label>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" name="SIGN_IN_WITH_APPLE_CLIENT_SECRET"
                                value="{{ env('SIGN_IN_WITH_APPLE_CLIENT_SECRET') }}"
                                placeholder="{{ translate('Apple Client Secret') }}" required>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
