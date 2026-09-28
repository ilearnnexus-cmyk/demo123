@extends('frontend.layouts.member_panel')
@section('panel_content')
      @if(Auth::user()->membership == 2)

        @php $happy_story = \App\Models\HappyStory::where('user_id', Auth::user()->id)->first(); @endphp
        @if(empty($happy_story))
           @include('frontend.member.happy_story.create')
        @else
          @include('frontend.member.happy_story.view')
        @endif

      @else
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Your Story')}}</h5>
            </div>
            <div class="card-body">
                <h5 class="text-center">{{ translate('Please Upgrade To Premium Membership To Post Your Story') }}</h5>
                <a href="{{ route('packages') }}" class="btn btn-block btn-primary mt-4 mb-6">{{ translate('Get Premium Membership') }}</a>
            </div>
        </div>
      @endif
@endsection
