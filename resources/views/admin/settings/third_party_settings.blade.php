@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0 h6">{{translate('Google reCAPTCHA Setting')}}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('third_party_settings.update') }}" method="POST">
                    <input type="hidden" name="setting_type" value="google_recaptcha">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="control-label">{{translate('Activation')}}</label>
                        </div>
                        <div class="col-md-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="google_recaptcha_activation" type="checkbox" @if (get_setting('google_recaptcha_activation') == 1)
                                    checked
                                @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="CAPTCHA_KEY">
                        <div class="col-md-3">
                            <label class="control-label">{{translate('Site KEY')}}</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="CAPTCHA_KEY" value="{{  env('CAPTCHA_KEY') }}" placeholder="{{ translate('Site KEY') }}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="RECAPTCHA_SECRET_KEY">
                        <div class="col-md-3">
                            <label class="control-label">{{translate('SECRET KEY')}}</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="RECAPTCHA_SECRET_KEY" value="{{  env('RECAPTCHA_SECRET_KEY') }}" placeholder="{{ translate('SECRET KEY') }}" required>
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
                <h3 class="mb-0 h6">{{translate('Google Analytics Settings')}}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('third_party_settings.update') }}" method="POST">
                    <input type="hidden" name="setting_type" value="google_analytics">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="control-label">{{translate('Activation')}}</label>
                        </div>
                        <div class="col-md-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input value="1" name="google_analytics_activation" type="checkbox" @if (get_setting('google_analytics_activation') == 1)
                                    checked
                                @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <input type="hidden" name="types[]" value="GOOGLE_ANALYTICS_TRACKING_ID">
                        <div class="col-md-3">
                            <label class="control-label">{{translate('Tracking ID')}}</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="GOOGLE_ANALYTICS_TRACKING_ID" value="{{  env('GOOGLE_ANALYTICS_TRACKING_ID') }}" placeholder="{{ translate('Tracking ID') }}" required>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Facebook Chat Setting -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="fw-600 mb-0">{{ translate('Facebook Chat') }}</h6>
            </div>
            <div class="card-body">
                <div class="row gutters-10">
                    <div class="col-lg-6">
                        <div class="card shadow-none bg-light">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('Facebook Chat Setting')}}</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('third_party_settings.update') }}" method="POST">
                                <input type="hidden" name="setting_type" value="facebook_chat">

                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label class="col-from-label">{{translate('Facebook Chat')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input value="1" name="facebook_chat_activation" type="checkbox" @if (get_setting('facebook_chat_activation') == 1)
                                                    checked
                                                @endif>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="hidden" name="types[]" value="FACEBOOK_PAGE_ID">
                                        <div class="col-md-3">
                                            <label class="col-from-label">{{translate('Facebook Page ID')}}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" name="FACEBOOK_PAGE_ID" value="{{  env('FACEBOOK_PAGE_ID') }}" placeholder="{{ translate('Facebook Page ID') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 text-right">
                                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow-none bg-light">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ translate('Please be carefull when you are configuring Facebook chat. For incorrect configuration you will not get messenger icon on your user-end site.') }}</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group mar-no">
                                    <li class="list-group-item text-dark">1. {{ translate('Login into your facebook page') }}</li>
                                    <li class="list-group-item text-dark">2. {{ translate('Find the About option of your facebook page') }}.</li>
                                    <li class="list-group-item text-dark">3. {{ translate('At the very bottom, you can find the \“Facebook Page ID\”') }}.</li>
                                    <li class="list-group-item text-dark">4. {{ translate('Go to Settings of your page and find the option of \"Advance Messaging\"') }}.</li>
                                    <li class="list-group-item text-dark">5. {{ translate('Scroll down that page and you will get \"white listed domain\"') }}.</li>
                                    <li class="list-group-item text-dark">6. {{ translate('Set your website domain name') }}.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Facebook Pixel Setting--}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="fw-600 mb-0">{{ translate('Facebook Pixel') }}</h6>
            </div>
            <div class="card-body">
                <div class="row gutters-10">
                    <div class="col-lg-6">
                        <div class="card shadow-none bg-light">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ translate('Facebook Pixel Setting') }}</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('third_party_settings.update') }}" method="POST">
                                <input type="hidden" name="setting_type" value="facebook_pixel">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label class="col-from-label">{{ translate('Facebook Pixel') }}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input value="1" name="facebook_pixel_activation" type="checkbox" @if (get_setting('facebook_pixel_activation') == 1)
                                                    checked
                                                @endif>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="hidden" name="types[]" value="FACEBOOK_PIXEL_ID">
                                        <div class="col-lg-3">
                                            <label class="col-from-label">{{ translate('Facebook Pixel ID') }}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" name="FACEBOOK_PIXEL_ID" value="{{  env('FACEBOOK_PIXEL_ID') }}" placeholder="{{ translate('Facebook Pixel ID') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 text-right">
                                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow-none bg-light">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ translate('Please be carefull when you are configuring Facebook pixel.') }}</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group mar-no">
                                    <li class="list-group-item text-dark">1. {{ translate('Log in to Facebook and go to your Ads Manager account') }}.</li>
                                    <li class="list-group-item text-dark">2. {{ translate('Open the Navigation Bar and select Events Manager') }}.</li>
                                    <li class="list-group-item text-dark">3. {{ translate('Copy your Pixel ID from underneath your Site Name and paste the number into Facebook Pixel ID field') }}.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Facebook Comment Setting--}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="fw-600 mb-0">{{ translate('Facebook Comment') }}</h6>
            </div>
            <div class="card-body">
                <div class="row gutters-10">
                    <div class="col-lg-6">
                        <div class="card shadow-none bg-light">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ translate('Facebook Comment Setting') }}</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('third_party_settings.update') }}" method="POST">
                                <input type="hidden" name="setting_type" value="facebook_comment">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label class="col-from-label">{{ translate('Facebook Comment') }}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input value="1" name="facebook_comment_activation" type="checkbox" @if (get_setting('facebook_comment_activation') == 1)
                                                    checked
                                                @endif>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <input type="hidden" name="types[]" value="FACEBOOK_APP_ID">
                                        <div class="col-lg-3">
                                            <label class="col-from-label">{{ translate('Facebook App ID') }}</label>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control" name="FACEBOOK_APP_ID" value="{{  env('FACEBOOK_APP_ID') }}" placeholder="{{ translate('Facebook App ID') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 text-right">
                                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Please be carefull when you are configuring Facebook Comment. For incorrect configuration you will not get comment section on your user-end site.') }}</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group mar-no">
                                <li class="list-group-item text-dark">
                                    1. {{ translate('Login into your facebook page') }}
                                </li>
                                <li class="list-group-item text-dark">
                                    2. {{ translate('After then go to this URL https://developers.facebook.com/apps/') }}.
                                </li>
                                <li class="list-group-item text-dark">
                                    3. {{ translate('Create Your App') }}.
                                </li>
                                <li class="list-group-item text-dark">
                                    4. {{ translate('In Dashboard page you will get your App ID') }}.
                                </li>
                            </ul>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

</div>
@endsection
