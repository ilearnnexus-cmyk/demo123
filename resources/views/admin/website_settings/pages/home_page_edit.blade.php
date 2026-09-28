@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-xl-10 mx-auto">
        <h6 class="fw-600">{{ translate('Home Page Settings') }}</h6>
        <div class="accordion" id="accordionExample">

            <!-- Home Slider -->
            <div class="card">
                <div class="card-header" id="headingHomeSlider" data-toggle="collapse" data-target="#collapseHomeSlider" aria-expanded="true" aria-controls="collapseHomeSlider">
                    <button class="btn btn-link fs-16 text-reset text-decoration-none" type="button">{{ translate('Home Page Slider') }}</button>
                </div>
                <div id="collapseHomeSlider" class="collapse show" aria-labelledby="headingHomeSlider" data-parent="#accordionExample">
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Show Home Page Slider?')}}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="hidden" name="types[]" value="show_homepage_slider">
                                    <input type="checkbox" name="show_homepage_slider" @if( get_setting('show_homepage_slider') == 'on') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Home Page Slider Text')}}</label>
                            <div class="col-md-9">
                                <input type="hidden" name="types[]" value="home_slider_text">
                                <textarea class="aiz-text-editor form-control" name="home_slider_text" data-buttons='[["font", ["bold", "underline", "italic"]],["color", ["color"]],["style", ["style"]],["para", ["ul", "ol"]],["view", ["undo","redo"]]]' placeholder="Type.." data-min-height="150">{!! get_setting('home_slider_text') !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Show right side registration form?')}}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="hidden" name="types[]" value="show_homepage_slider_registration">
                                    <input type="checkbox" name="show_homepage_slider_registration" @if( get_setting('show_homepage_slider_registration') == 'on') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ translate('Photos') }}</label>
                            <div class="home-slider-target">
                            <input type="hidden" name="types[]" value="home_slider_images">
                                @if (get_setting('home_slider_images') != null)
                                @foreach (json_decode(get_setting('home_slider_images'), true) as $key => $value)
                                <div class="row gutters-5">
                                    <div class="col">
                                        <div class="form-group">
                                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                                </div>
                                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                <input type="hidden" name="types[]" value="home_slider_images">
                                                <input type="hidden" name="home_slider_images[]" class="selected-files" value="{{ json_decode(get_setting('home_slider_images'), true)[$key] }}">
                                            </div>
                                            <div class="file-preview box sm"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-auto">
                                        <div class="form-group">
                                            <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                                <i class="las la-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                            <button
                                type="button"
                                class="btn btn-soft-secondary btn-sm"
                                data-toggle="add-more"
                                data-content='
                                    <div class="row gutters-5">
                                    <div class="col">
                                    <div class="form-group">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="home_slider_images">
                                    <input type="hidden" name="home_slider_images[]" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    </div>
                                    </div>
                                    <div class="col-md-auto">
                                    <div class="form-group">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                    <i class="las la-times"></i>
                                    </button>
                                    </div>
                                    </div>
                                    </div>'
                                data-target=".home-slider-target"
                            >
                                {{ translate('Add New') }}
                            </button>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Premium member Section -->
            <div class="card">
                <div class="card-header collapsed" id="headingPremiumMember" data-toggle="collapse" data-target="#collapsePremiumMember" aria-expanded="true" aria-controls="collapsePremiumMember">
                    <button class="btn btn-link fs-16 text-reset text-decoration-none" type="button">{{ translate('Premium Member Section') }}</button>
                </div>

                <div id="collapsePremiumMember" class="collapse" aria-labelledby="headingPremiumMember" data-parent="#accordionExample">
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
    				                @csrf
                            <div class="form-group row">
          						<label class="col-md-3 col-from-label">{{translate('Show Premium Member Section?')}}</label>
          						<div class="col-md-9">
          							<label class="aiz-switch aiz-switch-success mb-0">
          								<input type="hidden" name="types[]" value="show_premium_member_section">
          								<input type="checkbox" name="show_premium_member_section" @if( get_setting('show_premium_member_section') == 'on') checked @endif>
          								<span></span>
          							</label>
          						</div>
          					</div>
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Title')}}</label>
                              <div class="col-md-9">
                                <input type="hidden" name="types[]" value="premium_member_section_title">
                                <input type="text" name="premium_member_section_title" value='{{  get_setting('premium_member_section_title') }}' class="form-control" placeholder="{{ translate('Title') }}">
                              </div>
                            </div>
                            <div class="form-group row">
                  						<label class="col-md-3 col-from-label">{{translate('Sub Title')}}</label>
                              <div class="col-md-9">
                                  <input type="hidden" name="types[]" value="premium_member_section_sub_title">
                                  <textarea type="text" name="premium_member_section_sub_title" class="form-control" rows="5" placeholder="{{ translate('Sub Title') }}" >{{  get_setting('premium_member_section_sub_title') }}</textarea>
                              </div>
                  					</div>
                            <div class="form-group row">
                  						<label class="col-md-3 col-from-label">{{translate('Max Premium Member')}}</label>
                              <div class="col-md-9">
                                  <input type="hidden" name="types[]" value="max_premium_member_homepage">
                                  <input type="number" name="max_premium_member_homepage" value="{{  get_setting('max_premium_member_homepage') }}" class="form-control">
                              </div>
                  					</div>
                  					<div class="text-right">
                  						<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                  					</div>
                        </form>
    		           </div>
                </div>
            </div>

            <!-- Home Page Banner 1 -->
            <div class="card">
                <div class="card-header collapsed" id="headingBanner1" data-toggle="collapse" data-target="#collapseBanner1" aria-expanded="true" aria-controls="collapseBanner1">
                    <button class="btn btn-link fs-16 text-reset text-decoration-none" type="button">{{ translate('Home Page Banner 1 (Max 3)') }}</button>
                </div>

                <div id="collapseBanner1" class="collapse" aria-labelledby="headingBanner1" data-parent="#accordionExample">
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Show Banner 1 Section?')}}</label>
                                <div class="col-md-9">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="hidden" name="types[]" value="show_home_banner1_section">
                                        <input type="checkbox" name="show_home_banner1_section" @if( get_setting('show_home_banner1_section') == 'on') checked @endif>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{ translate('Banner & Links') }} ({{ translate('Size') }} : 1200x600)</label>
                                <div class="home-banner1-target">
                                    <input type="hidden" name="types[]" value="home_banner1_images">
                                    <input type="hidden" name="types[]" value="home_banner1_links">
                                    @if (get_setting('home_banner1_images') != null)
                                        @foreach (json_decode(get_setting('home_banner1_images'), true) as $key => $value)
                                        <div class="row gutters-5">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                                        </div>
                                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                        <input type="hidden" name="types[]" value="home_banner1_images">
                                                        <input type="hidden" name="home_banner1_images[]" class="selected-files" value="{{ json_decode(get_setting('home_banner1_images'), true)[$key] }}">
                                                    </div>
                                                    <div class="file-preview box sm">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md">
                                                <div class="form-group">
                                                    <input type="hidden" name="types[]" value="home_banner1_links">
                                                    <input type="text" class="form-control" placeholder="http://" name="home_banner1_links[]" value="{{ json_decode(get_setting('home_banner1_links'), true)[$key] }}">
                                                </div>
                                            </div>
                                            <div class="col-md-auto">
                                                <div class="form-group">
                                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button
                                    type="button"
                                    class="btn btn-soft-secondary btn-sm"
                                    data-toggle="add-more"
                                    data-content='
                                    <div class="row gutters-5">
                                    <div class="col-md-5">
                                    <div class="form-group">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="home_banner1_images">
                                    <input type="hidden" name="home_banner1_images[]" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    </div>
                                    </div>
                                    <div class="col-md">
                                    <div class="form-group">
                                    <input type="hidden" name="types[]" value="home_banner1_links">
                                    <input type="text" class="form-control" placeholder="http://" name="home_banner1_links[]">
                                    </div>
                                    </div>
                                    <div class="col-md-auto">
                                    <div class="form-group">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                    <i class="las la-times"></i>
                                    </button>
                                    </div>
                                    </div>
                                    </div>'
                                    data-target=".home-banner1-target">
                                    {{ translate('Add New') }}
                                </button>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- How it Works Section  -->
            <div class="card">
                <div class="card-header collapsed" id="headingHowItWorks" data-toggle="collapse" data-target="#collapseHowItWorks" aria-expanded="true" aria-controls="collapseOne">
                    <button class="btn btn-link fs-16 text-reset text-decoration-none" type="button">{{ translate('How it Works Section') }}</button>
                </div>

                <div id="collapseHowItWorks" class="collapse" aria-labelledby="headingHowItWorks" data-parent="#accordionExample">
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
    			                  @csrf
                            <div class="form-group row">
                  						<label class="col-md-3 col-from-label">{{translate('Show How it Works Section?')}}</label>
                  						<div class="col-md-9">
                  							<label class="aiz-switch aiz-switch-success mb-0">
                  								<input type="hidden" name="types[]" value="show_how_it_works_section">
                  								<input type="checkbox" name="show_how_it_works_section" @if( get_setting('show_how_it_works_section') == 'on') checked @endif>
                  								<span></span>
                  							</label>
                  						</div>
                  					</div>
                            <div class="form-group row">
    					                  <label class="col-md-3 col-from-label">{{translate('Title')}}</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="types[]" value="how_it_works_title">
                                    <input type="text" name="how_it_works_title" value="{{  get_setting('how_it_works_title') }}" class="form-control" rows="5" placeholder="{{ translate('Title') }}">
                                </div>
    			                  </div>
                            <div class="form-group row">
    					                  <label class="col-md-3 col-from-label">{{translate('Sub Title')}}</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="types[]" value="how_it_works_sub_title">
                                  <textarea type="text" name="how_it_works_sub_title" class="form-control" rows="5" placeholder="{{ translate('Sub Title') }}" >{{  get_setting('how_it_works_sub_title') }}</textarea>
                                </div>
    				                </div>

                            <div class="form-group">
                              <label>{{ translate('Steps').' ('.translate('Max 4').')' }}</label>
                              <div class="how_it_works_target">
                                <input type="hidden" name="types[]" value="how_it_works_steps_icons">
                                <input type="hidden" name="types[]" value="how_it_works_steps_titles">
                                <input type="hidden" name="types[]" value="how_it_works_steps_sub_titles">
                                @if (get_setting('how_it_works_steps_icons') != null)
                                  @foreach (json_decode(get_setting('how_it_works_steps_icons'), true) as $key => $value)
                                    <div class="row gutters-5">
                                      <div class="col-md-3">
                                        <div class="form-group">
                                          <div class="input-group" data-toggle="aizuploader" data-type="image">
                                              <div class="input-group-prepend">
                                                  <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                              </div>
                                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                <input type="hidden" name="types[]" value="how_it_works_steps_icons">
                                                <input type="hidden" name="how_it_works_steps_icons[]" class="selected-files" value="{{ json_decode(get_setting('how_it_works_steps_icons'), true)[$key] }}">
                                            </div>
                                            <div class="file-preview box sm"></div>
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div class="form-group">
                                          <input type="hidden" name="types[]" value="how_it_works_steps_titles">
                                          <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="how_it_works_steps_titles[]" value="{{ json_decode(get_setting('how_it_works_steps_titles'), true)[$key] }}">
                                        </div>
                                      </div>
                                      <div class="col-md-5">
                                        <div class="form-group">
                                          <input type="hidden" name="types[]" value="how_it_works_steps_sub_titles">
                                          <input type="text" class="form-control" placeholder="{{ translate('Sub Title') }}" name="how_it_works_steps_sub_titles[]" value="{{ json_decode(get_setting('how_it_works_steps_sub_titles'), true)[$key] }}">
                                        </div>
                                      </div>
                                      <div class="col-md-auto">
                                        <div class="form-group">
                                          <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                            <i class="las la-times"></i>
                                          </button>
                                        </div>
                                      </div>
                                    </div>
                                  @endforeach
                                @endif
                              </div>
                              <button
                                type="button"
                                class="btn btn-soft-secondary btn-sm"
                                data-toggle="add-more"
                                data-content='
                                <div class="row gutters-5">
                                  <div class="col-md-3">
                                    <div class="form-group">
                                      <div class="input-group" data-toggle="aizuploader" data-type="image">
                                          <div class="input-group-prepend">
                                              <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                          </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="types[]" value="how_it_works_steps_icons">
                                            <input type="hidden" name="how_it_works_steps_icons[]" class="selected-files" >
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                  </div>
                                  <div class="col-md-3">
                                    <div class="form-group">
                                      <input type="hidden" name="types[]" value="how_it_works_steps_titles">
                                      <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="how_it_works_steps_titles[]" >
                                    </div>
                                  </div>
                                  <div class="col-md-5">
                                    <div class="form-group">
                                      <input type="hidden" name="types[]" value="how_it_works_steps_sub_titles">
                                      <input type="text" class="form-control" placeholder="{{ translate('Sub Title') }}" name="how_it_works_steps_sub_titles[]">
                                    </div>
                                  </div>
                                  <div class="col-md-auto">
                                    <div class="form-group">
                                      <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                      </button>
                                    </div>
                                  </div>
                                </div>'
                                data-target=".how_it_works_target">
                                {{ translate('Add New') }}
                              </button>
                            </div>

                            <div class="text-right">
                            	<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                            </div>
    			              </form>
    			           </div>
                </div>
            </div>

            <!-- Trusted by Millions Section -->
            <div class="card">
                <div class="card-header collapsed" id="headingTrustedByMillions" data-toggle="collapse" data-target="#collapseTrustedByMillions" aria-expanded="true" aria-controls="collapseTrustedByMillions">
                    <button class="btn btn-link fs-16 text-reset text-decoration-none" type="button">{{ translate('Trusted by Millions Section') }}</button>
                </div>

                <div id="collapseTrustedByMillions" class="collapse" aria-labelledby="headingTrustedByMillions" data-parent="#accordionExample">
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
    			                  @csrf
                            <div class="form-group row">
                  						<label class="col-md-3 col-from-label">{{translate('Show Trusted by Millions Section?')}}</label>
                  						<div class="col-md-9">
                  							<label class="aiz-switch aiz-switch-success mb-0">
                  								<input type="hidden" name="types[]" value="show_trusted_by_millions_section">
                  								<input type="checkbox" name="show_trusted_by_millions_section" @if( get_setting('show_trusted_by_millions_section') == 'on') checked @endif>
                  								<span></span>
                  							</label>
                  						</div>
                  					</div>
                            <div class="form-group row">
    					                  <label class="col-md-3 col-from-label">{{translate('Background Image')}}</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="types[]" value="trusted_by_millions_background_image">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="trusted_by_millions_background_image" class="selected-files" value="{{ get_setting('trusted_by_millions_background_image')}}">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
    			                  </div>
                            <div class="form-group row">
    					                  <label class="col-md-3 col-from-label">{{translate('Title')}}</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="types[]" value="trusted_by_millions_title">
                                    <input type="text" name="trusted_by_millions_title" value="{{  get_setting('trusted_by_millions_title') }}" class="form-control" rows="5" placeholder="{{ translate('Title') }}">
                                </div>
    			                  </div>
                            <div class="form-group row">
    					                  <label class="col-md-3 col-from-label">{{translate('Sub Title')}}</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="types[]" value="trusted_by_millions_sub_title">
                                  <textarea type="text" name="trusted_by_millions_sub_title" class="form-control" rows="5" placeholder="{{ translate('Sub Title') }}" >{{ get_setting('trusted_by_millions_sub_title') }}</textarea>
                                </div>
    				                </div>

                            <div class="form-group">
                              <label>{{ translate('Best Features').' ('.translate('Max 4').')' }}</label>
                              <div class="trusted_by_millions_target">
                                <input type="hidden" name="types[]" value="homepage_best_features_icons">
                                <input type="hidden" name="types[]" value="homepage_best_features">
                                @if (get_setting('homepage_best_features') != null)
                                  @foreach (json_decode(get_setting('homepage_best_features'), true) as $key => $value)
                                    <div class="row gutters-5">
                                      <div class="col-md-5">
                                        <div class="form-group">
                                          <div class="input-group" data-toggle="aizuploader" data-type="image">
                                              <div class="input-group-prepend">
                                                  <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                              </div>
                                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                <input type="hidden" name="types[]" value="homepage_best_features_icons">
                                                <input type="hidden" name="homepage_best_features_icons[]" class="selected-files" value="{{ json_decode(get_setting('homepage_best_features_icons'), true)[$key] }}">
                                            </div>
                                            <div class="file-preview box sm"></div>
                                        </div>
                                      </div>
                                      <div class="col-md">
                                        <div class="form-group">
                                          <input type="hidden" name="types[]" value="homepage_best_features">
                                          <input type="text" class="form-control" placeholder="{{ translate('Feature') }}" name="homepage_best_features[]" value="{{ json_decode(get_setting('homepage_best_features'), true)[$key] }}">
                                        </div>
                                      </div>
                                      <div class="col-md-auto">
                                        <div class="form-group">
                                          <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                            <i class="las la-times"></i>
                                          </button>
                                        </div>
                                      </div>
                                    </div>
                                  @endforeach
                                @endif
                              </div>
                              <button
                                type="button"
                                class="btn btn-soft-secondary btn-sm"
                                data-toggle="add-more"
                                data-content='
                                <div class="row gutters-5">
                                  <div class="col-md-5">
                                    <div class="form-group">
                                      <div class="input-group" data-toggle="aizuploader" data-type="image">
                                          <div class="input-group-prepend">
                                              <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                          </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="types[]" value="homepage_best_features_icons">
                                            <input type="hidden" name="homepage_best_features_icons[]" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                  </div>
                                  <div class="col-md">
                                    <div class="form-group">
                                      <input type="hidden" name="types[]" value="homepage_best_features">
                                      <input type="text" class="form-control" placeholder="{{ translate('Feature') }}" name="homepage_best_features[]">
                                    </div>
                                  </div>
                                  <div class="col-md-auto">
                                    <div class="form-group">
                                      <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                      </button>
                                    </div>
                                  </div>
                                </div>'
                                data-target=".trusted_by_millions_target">
                                {{ translate('Add New') }}
                              </button>
                            </div>

                            <div class="text-right">
                            	<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                            </div>
    			              </form>
    			           </div>
                </div>
            </div>

            <!-- New member Section -->
            <div class="card">
                <div class="card-header collapsed" id="headingNewMember" data-toggle="collapse" data-target="#collapseNewMember" aria-expanded="true" aria-controls="collapseNewMember">
                    <button class="btn btn-link fs-16 text-reset text-decoration-none" type="button">{{ translate('New Member Section') }}</button>
                </div>

                <div id="collapseNewMember" class="collapse" aria-labelledby="headingNewMember" data-parent="#accordionExample">
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Show New Member Section?')}}</label>
                              <div class="col-md-9">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                  <input type="hidden" name="types[]" value="show_new_member_section">
                                  <input type="checkbox" name="show_new_member_section" @if( get_setting('show_new_member_section') == 'on') checked @endif>
                                  <span></span>
                                </label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Title')}}</label>
                              <div class="col-md-9">
                                <input type="hidden" name="types[]" value="new_member_section_title">
                                <input type="text" name="new_member_section_title" value='{{  get_setting('new_member_section_title') }}' class="form-control" placeholder="{{ translate('Title') }}">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Sub Title')}}</label>
                              <div class="col-md-9">
                                  <input type="hidden" name="types[]" value="new_member_section_sub_title">
                                  <textarea type="text" name="new_member_section_sub_title" class="form-control" rows="5" placeholder="{{ translate('Sub Title') }}" >{{  get_setting('new_member_section_sub_title') }}</textarea>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Max New Member')}}</label>
                              <div class="col-md-9">
                                  <input type="hidden" name="types[]" value="max_new_member_show_homepage">
                                  <input type="number" name="max_new_member_show_homepage" value="{{  get_setting('max_new_member_show_homepage') }}" class="form-control">
                              </div>
                            </div>
                            <div class="text-right">
                              <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                            </div>
                        </form>
                   </div>
                </div>
            </div>

            <!-- Happy Story Section -->
            <div class="card">
                <div class="card-header collapsed" id="headingHappyStory" data-toggle="collapse" data-target="#collapseHappyStory" aria-expanded="true" aria-controls="collapseHappyStory">
                    <button class="btn btn-link fs-16 text-reset text-decoration-none" type="button">{{ translate('Happy Story Section') }}</button>
                </div>

                <div id="collapseHappyStory" class="collapse" aria-labelledby="headingHappyStory" data-parent="#accordionExample">
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Show Happy Story Section?')}}</label>
                              <div class="col-md-9">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                  <input type="hidden" name="types[]" value="show_happy_story_section">
                                  <input type="checkbox" name="show_happy_story_section" @if( get_setting('show_happy_story_section') == 'on') checked @endif>
                                  <span></span>
                                </label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Title')}}</label>
                              <div class="col-md-9">
                                <input type="hidden" name="types[]" value="happy_story_section_title">
                                <input type="text" name="happy_story_section_title" value='{{  get_setting('happy_story_section_title') }}' class="form-control" placeholder="{{ translate('Title') }}">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Max Happy Story Show')}}</label>
                              <div class="col-md-9">
                                  <input type="hidden" name="types[]" value="max_happy_story_show_homepage">
                                  <input type="number" name="max_happy_story_show_homepage" value="{{  get_setting('max_happy_story_show_homepage') }}" class="form-control">
                              </div>
                            </div>
                            <div class="text-right">
                              <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                            </div>
                        </form>
                   </div>
                </div>
            </div>

            <!-- Package Section -->
            <div class="card">
                <div class="card-header collapsed" id="headingPackage" data-toggle="collapse" data-target="#collapsePackage" aria-expanded="true" aria-controls="collapsePackage">
                    <button class="btn btn-link fs-16 text-reset text-decoration-none" type="button">{{ translate('Package Section') }}</button>
                </div>

                <div id="collapsePackage" class="collapse" aria-labelledby="headingPackage" data-parent="#accordionExample">
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Show Happy Story Section?')}}</label>
                              <div class="col-md-9">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                  <input type="hidden" name="types[]" value="show_homapege_package_section">
                                  <input type="checkbox" name="show_homapege_package_section" @if( get_setting('show_homapege_package_section') == 'on') checked @endif>
                                  <span></span>
                                </label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Title')}}</label>
                              <div class="col-md-9">
                                <input type="hidden" name="types[]" value="homepage_package_section_title">
                                <input type="text" name="homepage_package_section_title" value='{{  get_setting('homepage_package_section_title') }}' class="form-control" placeholder="{{ translate('Title') }}">
                              </div>
                            </div>
                            <div class="form-group row">
                  						<label class="col-md-3 col-from-label">{{translate('Sub Title')}}</label>
                              <div class="col-md-9">
                                  <input type="hidden" name="types[]" value="homepage_package_section_sub_title">
                                  <textarea type="text" name="homepage_package_section_sub_title" class="form-control" rows="5" placeholder="{{ translate('Sub Title') }}" >{{ get_setting('homepage_package_section_sub_title') }}</textarea>
                              </div>
                  					</div>
                            <div class="text-right">
                              <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                            </div>
                        </form>
                   </div>
                </div>
            </div>

            <!-- Review Section -->
            <div class="card">
                <div class="card-header collapsed" id="headingReview" data-toggle="collapse" data-target="#collapseReview" aria-expanded="true" aria-controls="collapseReview">
                    <button class="btn btn-link fs-16 text-reset text-decoration-none" type="button">{{ translate('Reviews Section') }}</button>
                </div>

                <div id="collapseReview" class="collapse" aria-labelledby="headingReview" data-parent="#accordionExample">
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Show Review Section?')}}</label>
                              <div class="col-md-9">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                  <input type="hidden" name="types[]" value="show_homepage_review_section">
                                  <input type="checkbox" name="show_homepage_review_section" @if( get_setting('show_homepage_review_section') == 'on') checked @endif>
                                  <span></span>
                                </label>
                              </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Background Image')}}</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="types[]" value="homepage_review_section_background_image">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="homepage_review_section_background_image" class="selected-files" value="{{ get_setting('homepage_review_section_background_image')}}">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{translate('Title')}}</label>
                                <div class="col-md-9">
                                    <input type="hidden" name="types[]" value="homepage_review_section_title">
                                    <input type="text" name="homepage_review_section_title" value="{{  get_setting('homepage_review_section_title') }}" class="form-control" rows="5" placeholder="{{ translate('Title') }}">
                                </div>
                            </div>

                            <div class="form-group">
                              <label>{{ translate('Reviews') }}</label>
                              <div class="trusted_by_millions_target">
                                <input type="hidden" name="types[]" value="homepage_reviewers_images">
                                <input type="hidden" name="types[]" value="homepage_reviews">
                                @if (get_setting('homepage_reviews') != null)
                                  @foreach (json_decode(get_setting('homepage_reviews'), true) as $key => $value)
                                    <div class="row gutters-5">
                                      <div class="col-md-4">
                                        <div class="form-group">
                                          <div class="input-group" data-toggle="aizuploader" data-type="image">
                                              <div class="input-group-prepend">
                                                  <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                              </div>
                                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                <input type="hidden" name="types[]" value="homepage_reviewers_images">
                                                <input type="hidden" name="homepage_reviewers_images[]" class="selected-files" value="{{ json_decode(get_setting('homepage_reviewers_images'), true)[$key] }}">
                                            </div>
                                            <div class="file-preview box sm"></div>
                                        </div>
                                      </div>
                                      <div class="col-md">
                                        <div class="form-group">
                                          <input type="hidden" name="types[]" value="homepage_reviews">
                                          <textarea type="text" class="form-control" placeholder="{{ translate('Review') }}" name="homepage_reviews[]">{{ json_decode(get_setting('homepage_reviews'), true)[$key] }}</textarea>
                                        </div>
                                      </div>
                                      <div class="col-md-auto">
                                        <div class="form-group">
                                          <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                            <i class="las la-times"></i>
                                          </button>
                                        </div>
                                      </div>
                                    </div>
                                  @endforeach
                                @endif
                              </div>
                              <button
                                type="button"
                                class="btn btn-soft-secondary btn-sm"
                                data-toggle="add-more"
                                data-content='
                                <div class="row gutters-5">
                                  <div class="col-md-5">
                                    <div class="form-group">
                                      <div class="input-group" data-toggle="aizuploader" data-type="image">
                                          <div class="input-group-prepend">
                                              <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                          </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="types[]" value="homepage_reviewers_images">
                                            <input type="hidden" name="homepage_reviewers_images[]" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                  </div>
                                  <div class="col-md">
                                    <div class="form-group">
                                      <input type="hidden" name="types[]" value="homepage_reviews">
                                      <textarea type="text" class="form-control" placeholder="{{ translate('Review') }}" name="homepage_reviews[]"></textarea>
                                    </div>
                                  </div>
                                  <div class="col-md-auto">
                                    <div class="form-group">
                                      <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                      </button>
                                    </div>
                                  </div>
                                </div>'
                                data-target=".trusted_by_millions_target">
                                {{ translate('Add New') }}
                              </button>
                            </div>

                            <div class="text-right">
                              <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                            </div>
                        </form>
                     </div>
                </div>
            </div>

            <!-- Blog Section -->
            <div class="card">
                <div class="card-header collapsed" id="headingBlogSection" data-toggle="collapse" data-target="#collapseBlogSection" aria-expanded="true" aria-controls="collapseBlogSection">
                    <button class="btn btn-link fs-16 text-reset text-decoration-none" type="button">{{ translate('Blog Section') }}</button>
                </div>

                <div id="collapseBlogSection" class="collapse" aria-labelledby="headingBlogSection" data-parent="#accordionExample">
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Show Blog Section?')}}</label>
                              <div class="col-md-9">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                  <input type="hidden" name="types[]" value="show_blog_section">
                                  <input type="checkbox" name="show_blog_section" @if( get_setting('show_blog_section') == 'on') checked @endif>
                                  <span></span>
                                </label>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Title')}}</label>
                              <div class="col-md-9">
                                <input type="hidden" name="types[]" value="blog_section_title">
                                <input type="text" name="blog_section_title" value='{{  get_setting('blog_section_title') }}' class="form-control" placeholder="{{ translate('Title') }}">
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-md-3 col-from-label">{{translate('Max Blog Show on Homepage')}}</label>
                              <div class="col-md-9">
                                  <input type="hidden" name="types[]" value="max_blog_show_homepage">
                                  <input type="number" name="max_blog_show_homepage" value="{{  get_setting('max_blog_show_homepage') }}" class="form-control">
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
    </div>
</div>
@endsection
