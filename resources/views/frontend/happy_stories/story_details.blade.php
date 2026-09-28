@extends('frontend.layouts.app')
@section('content')
    <section class="py-5 text-center bg-white">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <h1 class="fw-600 h2 lh-1-5 text-dark">{{ $happy_story->title }}</h1>
                    <div>
                        <span class="opacity-40">{{ translate('Posted By') }}:</span>
                        <a
                            @if(!Auth::check())
                                onclick="loginModal()"
                            @elseif(get_setting('full_profile_show_according_to_membership') == 1 && Auth::user()->membership == 1)
                                href="javascript:void(0);" onclick="package_update_alert()"
                            @else
                                href="{{ route('member_profile', $happy_story->user_id) }}"
                            @endif
                            class="c-pointer text-primary">
                            {{ $happy_story->user->first_name.' '.$happy_story->user->last_name.''  }}
                        </a>
                        <span class="opacity-40">{{ translate('On') }}:</span>
                        <span class="opacity-70">{{ $happy_story->created_at->format('d F, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4 bg-white">
        <div class="container">
            <div class="aiz-carousel dots-inside-bottom" data-arrows="true" data-dots="true" data-autoplay="true">
                @if ($happy_story->photos != null)
                    @foreach (explode(',',$happy_story->photos) as $key => $photo)
                        <div class="carousel-box">
                            <img class="d-block lazyload img-fluid mx-auto" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($photo) }}" alt="{{ $happy_story->title }}">
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="py-4">
                        <div class="overflow-hidden mb-4 lh-1-8">{!! $happy_story->details !!}</div>
                        <div class="">
                            <div class="embed-responsive embed-responsive-16by9">
                                @if ($happy_story->video_provider == 'youtube' && isset(explode('=', $happy_story->video_link)[1]))
                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ explode('=', $happy_story->video_link)[1] }}"></iframe>
                                @elseif ($happy_story->video_provider == 'dailymotion' && isset(explode('video/', $happy_story->video_link)[1]))
                                    <iframe class="embed-responsive-item" src="https://www.dailymotion.com/embed/video/{{ explode('video/', $happy_story->video_link)[1] }}"></iframe>
                                @elseif ($happy_story->video_provider == 'vimeo' && isset(explode('vimeo.com/', $happy_story->video_link)[1]))
                                    <iframe src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $happy_story->video_link)[1] }}" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if (get_setting('facebook_comment_activation') == 1)
                        <div class="border-top">
                            <div class="fb-comments" data-width="" data-numposts="5"></div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    @include('modals.login_modal')
    @include('modals.package_update_alert_modal')
@endsection

@section('script')
<script type="text/javascript">

	// Login alert
    function loginModal(){
        $('#LoginModal').modal();
    }

    // Package update alert
    function package_update_alert(){
      $('.package_update_alert_modal').modal('show');
    }

</script>

@if (get_setting('facebook_comment_activation') == 1)
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
    src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0&appId={{ env('FACEBOOK_APP_ID') }}&autoLogAppEvents=1"
    nonce="ji6tXwgZ">
</script>
@endif
@endsection
