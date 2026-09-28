@extends('admin.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Website Header') }}</h1>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8 mx-auto">
		<div class="card">
			<div class="card-header">
				<h6 class="mb-0">{{ translate('Header Setting') }}</h6>
			</div>
			<div class="card-body">
				<form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
				@csrf
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{ translate('Header Logo') }}</label>
						<div class="col-md-8">
							<div class=" input-group " data-toggle="aizuploader" data-type="image">
								<div class="input-group-prepend">
									<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
								</div>
								<div class="form-control file-amount">{{ translate('Choose File') }}</div>
								<input type="hidden" name="types[]" value="header_logo">
								<input type="hidden" name="header_logo" class="selected-files" value="{{ get_setting('header_logo') }}">
							</div>
							<div class="file-preview">
							</div>
						</div>
					</div>
					<div class="form-group row">
              <label class="col-md-3 col-from-label">{{translate('Header Left Quick Link')}}</label>
              <div class="col-md-3">
                  <input type="hidden" name="types[]" value="header_left_quick_link1_text">
                  <input type="text" name="header_left_quick_link1_text" class="form-control" placeholder="{{ translate('Text') }}" value="{{ get_setting('header_left_quick_link1_text') }}">
              </div>
							<div class="col-md-5">
                  <input type="hidden" name="types[]" value="header_left_quick_link1">
                  <input type="text" name="header_left_quick_link1" class="form-control" placeholder="{{ translate('Link') }}" value="{{ get_setting('header_left_quick_link1') }}">
              </div>
          </div>
					<div class="form-group row">
              <label class="col-md-3 col-from-label">{{translate('Helpline Number')}}</label>
              <div class="col-md-8">
                  <input type="hidden" name="types[]" value="header_helpline_no">
                  <input type="text" name="header_helpline_no" class="form-control" placeholder="{{ translate('Help Line Number') }}" value="{{ get_setting('header_helpline_no') }}">
              </div>
          </div>
					<div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Enable Sticky header?')}}</label>
						<div class="col-md-8">
							<label class="aiz-switch aiz-switch-success mb-0">
								<input type="hidden" name="types[]" value="header_stikcy">
								<input type="checkbox" name="header_stikcy" @if( get_setting('header_stikcy') == 'on') checked @endif>
								<span></span>
							</label>
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
@endsection
