@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
      <div class="card">
          <div class="card-header">
              <h5 class="mb-0 h6">{{translate('Story Details')}}</h5>
          </div>
          <div class="card-body">
            <div class="row align-items-center">
                <div class="col-8">
                    <span class="h6">{{ $happy_story->title }}</span>
                </div>
                <div class="col-4 text-right">
                    <span >
                      {{ translate('Posted By:').' '.$happy_story->user->first_name.' '.$happy_story->user->last_name }}
                    </span>
                </div>
            </div>

            <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height mt-4" data-arrows="true" data-dots="true" data-autoplay="true">
                @if ($happy_story->photos != null)
                    @foreach (explode(',',$happy_story->photos) as $key => $photo)
                        <div class="carousel-box">
                            <img class="d-block w-100 lazyload rounded h-200px h-lg-380px img-fit" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($photo) }}" alt="{{ $key }} offer">
                        </div>
                    @endforeach
                @endif
            </div>

            <div>{{ translate('Post Time:').' '.$happy_story->created_at }}</div>
            <div class="overflow-hidden mt-4">@php echo $happy_story->details; @endphp</div>

            <div class="p-4">
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
        </div>
    </div>
</div>

@endsection
