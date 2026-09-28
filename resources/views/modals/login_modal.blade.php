<div class="modal fade" id="LoginModal">
    <div class="modal-dialog modal-dialog-zoom">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">{{ translate('Login')}}</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-3">
                    <form class="" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="email">
                                {{ addon_activation('otp_system') ? translate('Email/Phone') : translate('Email') }}
                            </label>
                            @if (addon_activation('otp_system'))
                                <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone')}}" name="email" id="email">
                            @else
                                <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" id="email">
                            @endif
                            @if (addon_activation('otp_system'))
                                <span class="opacity-60">{{ translate('Use country code before number') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">{{ translate('Password') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="********" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 text-right">
                            <a class="link-muted text-capitalize font-weight-normal" href="{{ route('password.request') }}">{{ translate('Forgot Password?') }}</a>
                        </div>

                        {{-- <div class="mb-5"> --}}
                            <button type="submit" class="btn btn-block btn-primary">{{ translate('Login to your Account') }}</button>
                        {{-- </div> --}}
                    </form>
                    @if (env("DEMO_MODE") == "On")
                        <div class="mb-4 mt-4">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>user2@example.com</td>
                                        <td>12345678</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="autoFill1()">{{ translate('Copy') }}</button></td>
                                    </tr>
                                    <tr>
                                        <td>user17@example.com</td>
                                        <td>12345678</td>
                                        <td><button class="btn btn-outline-primary btn-xs" onclick="autoFill2()">{{ translate('Copy') }}</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif


                    {{-- Social media Login --}}
                    @if(get_setting('google_login_activation') == 1 || get_setting('facebook_login_activation') == 1 || get_setting('twitter_login_activation') == 1 || get_setting('apple_login_activation') == 1)
                        <div class="separator mb-3 mt-4">
                            <span class="bg-white px-3 opacity-60">{{ translate('Or Login With')}}</span>
                        </div>
                        <ul class="list-inline social colored text-center mb-3">
                            @if (get_setting('facebook_login_activation') == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook">
                                        <i class="lab la-facebook-f"></i>
                                    </a>
                                </li>
                            @endif
                            @if(get_setting('google_login_activation') == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                                        <i class="lab la-google"></i>
                                    </a>
                                </li>
                            @endif
                            @if (get_setting('twitter_login_activation') == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="twitter">
                                        <i class="lab la-twitter"></i>
                                    </a>
                                </li>
                            @endif
                            @if (get_setting('apple_login_activation') == 1)
                                <li class="list-inline-item">
                                    <a href="{{ route('social.login', ['provider' => 'apple']) }}" class="apple">
                                        <i class="lab la-apple"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endif

                    <div class="text-center">
                        <p class="text-muted mb-0">{{ translate("Don't have an account?") }}</p>
                        <a href="{{ route('register') }}">{{ translate('Create an account') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
