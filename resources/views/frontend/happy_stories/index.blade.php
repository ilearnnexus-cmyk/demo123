@extends('frontend.layouts.app')
@section('content')
<section class="pt-6 pb-4 bg-white text-center">
    <div class="container">
        <h1 class="fw-600 text-dark">{{ translate('Happy Stories')}}</h1>
    </div>
</section>
<section class="pt-5 pb-4 bg-white">
    <div class="container">
        <div class="card-columns column-gap-10 card-columns-xl-3 card-columns-md-2 card-columns-1">
            @foreach ($happy_stories as $key => $happy_story)
                @php
                    $photo = explode(',',$happy_story->photos);
                @endphp
    			<div class="card mb-3 shadow-none">
    				<a href="{{ route('story_details', $happy_story->id) }}" class="text-reset d-block mb-4">
    					<img src="{{ uploaded_asset($photo[0]) }}" class="img-fluid">
    				</a>
                    <div class="p-3">
        				<h2 class="h5">
        					<a href="{{ route('story_details', $happy_story->id) }}" class="text-dark">{{ $happy_story->title }}</a>
        				</h2>
                        <div class="mb-3">
                            <span class="opacity-40">{{ translate('Posted By') }}:</span>
                            <a
                                @if(!Auth::check())
                                    onclick="loginModal()"
                                @elseif(get_setting('full_profile_show_according_to_membership') == 1 && Auth::user()->membership == 1)
                                    href="javascript:void(0);" onclick="package_update_alert()"
                                @else
                                    href="{{ route('member_profile', $happy_story->user_id) }}"
                                @endif
                                class="c-pointer text-primary" >
                                {{ $happy_story->user->first_name.' '.$happy_story->user->last_name.''  }}
                            </a>
                            <span class="opacity-40">{{ translate('On') }}:</span>
                            <span class="opacity-70">{{ $happy_story->created_at->format('d F, Y') }}</span>
                        </div>
        				<a href="{{ route('story_details', $happy_story->id) }}" class="btn btn-primary mt-2">{{ translate('View Details') }}</a>
        			</div>
                </div>
            @endforeach
        </div>
        <div class="aiz-pagination aiz-pagination-center mt-4">
            {{ $happy_stories->appends(request()->input())->links() }}
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
@endsection
