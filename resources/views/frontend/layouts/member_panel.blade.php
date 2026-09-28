@extends('frontend.layouts.app')
@section('content')
<section class="py-5 bg-white">
	<div class="container">
		<div class="d-flex align-items-start">
			@include('frontend.member.sidebar')
			<div class="aiz-user-panel overflow-hidden">
				@yield('panel_content')
			</div>
		</div>
	</div>
</section>
@endsection

