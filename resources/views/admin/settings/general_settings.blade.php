@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0 h6">{{ translate('General Settings') }}</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('System Name') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="site_name">
                                <input type="text" name="site_name" class="form-control" value="{{ env('APP_NAME') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('System Logo') }}</label>
                            <div class="col-sm-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose Files') }}</div>
                                    <input type="hidden" name="types[]" value="system_logo">
                                    <input type="hidden" name="system_logo" value="{{ get_setting('system_logo') }}"
                                        class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('System Timezone') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="timezone">
                                <select name="timezone" class="form-control aiz-selectpicker" data-live-search="true">
                                    @foreach (timezones() as $key => $value)
                                        <option value="{{ $value }}" @if (env('APP_TIMEZONE') == $value)
                                            selected
                                    @endif>{{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Admin login page background') }}</label>
                            <div class="col-sm-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose Files') }}</div>
                                    <input type="hidden" name="types[]" value="admin_login_background">
                                    <input type="hidden" name="admin_login_background"
                                        value="{{ get_setting('admin_login_background') }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Member Code Prefix') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="member_code_prifix">
                                <input type="text" name="member_code_prifix" class="form-control"
                                    value="{{ get_setting('member_code_prifix') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Member Minimum Age') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="member_min_age">
                                <input type="number" name="member_min_age" step="1" class="form-control"
                                    value="{{ get_setting('member_min_age') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Member Profile Picture Privacy') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="profile_picture_privacy">
                                <select class="form-control aiz-selectpicker" data-live-search="true" name="profile_picture_privacy" required>
                                    <option value="all" @if(get_setting('profile_picture_privacy') == 'all') selected @endif>{{ translate('All') }}</option>
                                    <option value="only_me" @if(get_setting('profile_picture_privacy') == 'only_me') selected @endif>{{ translate('Only Me') }}</option>
                                    <option value="premium_members" @if(get_setting('profile_picture_privacy') == 'premium_members') selected @endif>{{ translate('Premium Members') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{ translate('Member Gallery Image Privacy') }}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="gallery_image_privacy">
                                <select class="form-control aiz-selectpicker" data-live-search="true" name="gallery_image_privacy" required>
                                    <option value="all" @if(get_setting('gallery_image_privacy') == 'all') selected @endif>{{ translate('All') }}</option>
                                    <option value="only_me" @if(get_setting('gallery_image_privacy') == 'only_me') selected @endif>{{ translate('Only Me') }}</option>
                                    <option value="premium_members" @if(get_setting('gallery_image_privacy') == 'premium_members') selected @endif>{{ translate('Premium Members') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0 h6">{{ translate('Activation') }}</h1>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-8 col-from-label">{{ translate('HTTPS Activation') }}</label>
                        <div class="col-sm-4">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'FORCE_HTTPS')" <?php if (env('FORCE_HTTPS') == 'On') {
    echo 'checked';
} ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-8 col-from-label">{{ translate('Maintenance Mode Activation') }}</label>
                        <div class="col-sm-4">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'maintenance_mode')"
                                    <?php if (\App\Models\Setting::where('type', 'maintenance_mode')->first()->value == 1) {
                                        echo 'checked';
                                    } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-8 col-from-label">{{ translate('Wallet System Activation') }}</label>
                        <div class="col-sm-4">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'wallet_system')" @if(get_setting('wallet_system')== 1) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-8">
                            <label class="col-from-label">{{ translate('Email/Phone Verification') }}
                                <i>
                                    <code>({{ translate('You need to configure SMTP correctly to enable this feature.') }}
                                        <a
                                            href="{{ route('smtp_settings') }}">{{ translate('Configure Now') }}</a>)</code>
                                </i>
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'email_verification')"
                                    <?php if (get_setting('email_verification') == 1) {
                                        echo 'checked';
                                    } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label
                            class="col-sm-8 col-from-label">{{ translate('Member Approval by Admin Activation') }}</label>
                        <div class="col-sm-4">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'member_approval_by_admin')"
                                    <?php if (get_setting('member_approval_by_admin') == 1) {
                                        echo 'checked';
                                    } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-8">
                            <label
                                class="col-from-label">{{ translate('Only Premium Member Can See Other Members Full Profile.') }}
                                <i>
                                    <code>({{ translate('If you disable this, all registered members will be able to see the members full profile.') }})</code>
                                </i>
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox"
                                    onchange="updateSettings(this, 'full_profile_show_according_to_membership')"
                                    <?php if (get_setting('full_profile_show_according_to_membership') == 1) {
                                        echo 'checked';
                                    } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-8">
                            <label class="col-from-label">{{ translate('Member Profile Picture Approval by Admin.') }}
                                <i>
                                    <code>({{ translate('If you disable this, all registered members will be able to see the members full profile.') }})</code>
                                </i>
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="updateSettings(this, 'profile_picture_approval_by_admin')"
                                    <?php if (get_setting('profile_picture_approval_by_admin') == 1) {
                                        echo 'checked';
                                    } ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function updateSettings(el, type) {
            if ($(el).is(':checked')) {
                var value = 1;
            } else {
                var value = 0;
            }
            $.post('{{ route('settings.activation.update') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                value: value
            }, function(data) {
                if (data == '1') {
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
