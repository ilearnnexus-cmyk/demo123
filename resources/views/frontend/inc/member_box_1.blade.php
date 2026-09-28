<div class="rounded border position-relative overflow-hidden">
	<a
		@if(!Auth::check())
			onclick="loginModal()"
		@elseif(get_setting('full_profile_show_according_to_membership') == 1 && Auth::user()->membership == 1)
            href="javascript:void(0);" onclick="package_update_alert()"
        @else
            href="{{ route('member_profile', $member->id) }}"
        @endif
		class="d-block text-reset c-pointer"
	>
		@php
			$avatar_image = optional($member->member)->gender == 1 ? 'assets/img/avatar-place.png' : 'assets/img/female-avatar-place.png';
			$profile_picture_show = show_profile_picture($member);
		@endphp
		<img
				@if($profile_picture_show)
					src="{{ uploaded_asset($member->photo) }}"
				@else
					src="{{ static_asset($avatar_image) }}"
				@endif
				onerror="this.onerror=null;this.src='{{ static_asset($avatar_image) }}';"
				class="img-fit mw-100 h-350px"
		>
		@if(!$profile_picture_show)
			<div class="absolute-full d-flex justify-content-center align-items-center bg-soft-dark text-white"><i class="las la-lock la-3x"></i></div>
		@endif

		<div class="absolute-bottom-left w-100 p-3 z-1">
			<div class="absolute-full bg-white opacity-90 z--1"></div>
			<div class="text-center">
				<div class="text-primary fw-500 mb-1">{{ $member->first_name}}</div>
            <div class="fs-10">
                <span class="opacity-60">{{ translate('Member ID: ') }}</span>
                <span class="ml-2 text-primary">{{ $member->code }}</span>
            </div>
			</div>
		</div>
	</a>
</div>
