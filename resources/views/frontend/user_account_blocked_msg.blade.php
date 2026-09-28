@extends('frontend.layouts.app')

@section('content')
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-8 col-md-10 mx-auto">
	            <div class="border text-center p-4 rounded">
	            	<i class="las la-ban la-5x text-danger mb-4"></i>
	                <h1 class="fw-700 h3">{{ translate('Your account has been banned.') }}</h1>
	                <div class="alert bg-soft-danger mb-0 mt-3">
	                	{{ Auth::user()->member->blocked_reason	 }}
	                </div>
	            </div>
            </div>
        </div>
    </div>
</section>
@endsection
