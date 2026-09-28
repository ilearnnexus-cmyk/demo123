<!doctype html>
@if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif

<head>
    <!-- Required meta tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ get_setting('website_name') . ' | ' . get_setting('site_motto') }}</title>

    <!-- Favicon -->
    <link name="favicon" type="image/x-icon" href="{{ uploaded_asset(get_setting('site_icon')) }}"
        rel="shortcut icon" />

    <!-- google font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

    <!-- vendors css -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">

    <!-- aiz core css -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css') }}">

    @if (\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
        <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif


    <script>
        var AIZ = AIZ || {};
    </script>

</head>

<body>

    <div class="aiz-main-wrapper">

        @include('admin.inc.sidenav')

        <div class="aiz-content-wrapper">

            @include('admin.inc.header')

            <!-- Main Content start-->
            <div class="aiz-main-content">
                <div class="px-15px px-lg-25px">
                    @yield('content')
                </div>

                <!-- Footer -->
                <div class="bg-white text-center py-3 px-15px px-lg-25px mt-auto">
                    <p class="mb-0">&copy; {{ env('APP_NAME') }} v{{ get_setting('current_version') }}</p>
                </div>
            </div>
            <!-- Mian content end -->

        </div>

    </div>

    @yield('modal')

    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js') }}"></script>

    {{-- fcm --}}
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>

    <!-- TODO: Add SDKs for Firebase products that you want to use
    https://firebase.google.com/docs/web/setup#available-libraries -->

    <script>
        // Your web app's Firebase configuration
        var firebaseConfig = {
            apiKey: "{{ env('FCM_API_KEY') }}",
            authDomain: "{{ env('FCM_AUTH_DOMAIN') }}",
            projectId: "{{ env('FCM_PROJECT_ID') }}",
            storageBucket: "{{ env('FCM_STORAGE_BUCKET') }}",
            messagingSenderId: "{{ env('FCM_MESSAGING_SENDER_ID') }}",
            appId: "{{ env('FCM_APP_ID') }}",
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        const messaging = firebase.messaging();

        function initFirebaseMessagingRegistration() {
            messaging.requestPermission()
            .then(function() {
                return messaging.getToken()
            }).then(function(token) {
                
                $.ajax({
                    url: '{{ route('fcmToken') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                       
                        fcm_token: token
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        
                    },
                    error: function (err) {
                        console.log(" Can't do because: " + err);
                    },
                });

            }).catch(function(err) {
                console.log(`Token Error :: ${err}`);
            });
        }

        initFirebaseMessagingRegistration();        

        messaging.onMessage(function({
            data: {
                body,
                title
            }
        }) {
            new Notification(title, {
                body
            });
        });
    </script>
    {{-- End of fcm --}}

    @yield('script')



    <script type="text/javascript">
        @foreach (session('flash_notification', collect())->toArray() as $message)
            AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @endforeach

        // language Switch
        if ($('#lang-change').length > 0) {
            $('#lang-change .dropdown-menu a').each(function() {
                $(this).on('click', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var locale = $this.data('flag');
                    $.post('{{ route('language.change') }}', {
                        _token: '{{ csrf_token() }}',
                        locale: locale
                    }, function(data) {
                        location.reload();
                    });

                });
            });
        }
    </script>


</body>

</html>
