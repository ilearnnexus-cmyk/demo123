@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6">{{ translate('Firebase Push Notification') }}</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('settings.fcm.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-from-label">{{translate('Activation')}}</label>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="firebase_push_notification" type="checkbox" @if (get_setting('firebase_push_notification') == 1)
                                        checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FCM_API_KEY">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FCM API KEY') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FCM_API_KEY"
                                    value="{{ env('FCM_API_KEY') }}" placeholder="{{ translate('FCM API KEY') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FCM_AUTH_DOMAIN">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FCM AUTH DOMAIN') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FCM_AUTH_DOMAIN"
                                    value="{{ env('FCM_AUTH_DOMAIN') }}" placeholder="{{ translate('FCM AUTH DOMAIN') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FCM_PROJECT_ID">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FCM PROJECT ID') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FCM_PROJECT_ID"
                                    value="{{ env('FCM_PROJECT_ID') }}" placeholder="{{ translate('FCM PROJECT ID') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FCM_STORAGE_BUCKET">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FCM STORAGE BUCKET') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FCM_STORAGE_BUCKET"
                                    value="{{ env('FCM_STORAGE_BUCKET') }}"
                                    placeholder="{{ translate('FCM STORAGE BUCKET') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FCM_MESSAGING_SENDER_ID">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FCM MESSAGING SENDER ID') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FCM_MESSAGING_SENDER_ID"
                                    value="{{ env('FCM_MESSAGING_SENDER_ID') }}"
                                    placeholder="{{ translate('FCM MESSAGING SENDER ID') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FCM_APP_ID">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FCM APP ID') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FCM_APP_ID"
                                    value="{{ env('FCM_APP_ID') }}" placeholder="{{ translate('FCM APP ID') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="FIREBASE_SERVER_KEY">
                            <div class="col-md-4">
                                <label class="control-label">{{ translate('FIREBASE SERVER KEY') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="FIREBASE_SERVER_KEY"
                                    value="{{ env('FIREBASE_SERVER_KEY') }}"
                                    placeholder="{{ translate('FIREBASE SERVER KEY') }}">
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-gray-light">
                <div class="card-header">
                    <h5 class="mb-0 h6">
                        {{ translate('Please be carefull when you are configuring Firebase Push Notification.') }}
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group mar-no">
                        <li class="list-group-item text-dark">
                            {{ translate("1. Log in to Google Firebase and Create a new app if you don't have any") }}.</li>
                        <li class="list-group-item text-dark">
                            {{ translate('2. Go to Project Settings and select General tab') }}.</li>
                        <li class="list-group-item text-dark">
                            {{ translate('3. Select Config and you will find Firebase Config Credentials') }}.
                        </li>
                        <li class="list-group-item text-dark">
                            {{ translate("4. Copy your App's Credentials and paste the Credentials into appropriate fields") }}
                        </li>
                        <li class="list-group-item text-dark">
                            {{ translate('5. Now, select Cloud Messaging tab and Enable Cloud Messaging API') }}
                        </li>
                        <li class="list-group-item text-dark">
                            {{ translate('6. After enabling Cloud Messaging API, you will find Server Key. Copy the key value and paste into FIREBASE SERVER KEY field') }}
                        </li>
                        <li class="list-group-item text-dark">
                            {{ translate('7. Configure the "firebase-messaging-sw.js" file and Keep the file in the root directory of your cPanel') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
