<div class="chat-box-wrap h-100">
    <div class="attached-top bg-white border-bottom chat-header d-flex justify-content-between align-items-center p-3 shadow-sm">
        <div class="media align-items-center">
            <span class="avatar avatar-sm mr-3 flex-shrink-0">
              @php
                  $current_user = Auth::user()->id;
              @endphp
                @if($current_user == $chat_thread->sender->id)
                  @php $user_to_show = 'receiver';  @endphp
                @else
                  @php $user_to_show = 'sender';  @endphp
                @endif

                @if ($chat_thread->$user_to_show->photo != null)
                    <img src="{{ uploaded_asset($chat_thread->$user_to_show->photo) }}">
                @else
                    <img src="{{ static_asset('assets/frontend/default/img/avatar-place.png') }}">
                @endif
            </span>
            <div class="media-body">
                <h6 class="fs-15 mb-1">
                    {{ $chat_thread->$user_to_show->first_name.' '.$chat_thread->$user_to_show->last_name }}
                    @if(Cache::has('user-is-online-' . $chat_thread->$user_to_show->id))
                        <span class="badge badge-dot badge-success badge-circle"></span>
                    @else
                        <span class="badge badge-dot badge-secondary badge-circle"></span>
                    @endif
                </h6>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <button class="aiz-mobile-toggler d-lg-none aiz-all-chat-toggler mr-2" data-toggle="class-toggle" data-target=".chat-user-list-wrap">
                <span></span>
            </button>
            <button class="btn btn-icon btn-circle btn-soft-primary chat-info" data-toggle="class-toggle" data-target=".chat-info-wrap"><i class="las la-info-circle"></i></button>
        </div>
    </div>
    <div class="chat-list-wrap c-scrollbar-light scroll-to-btm" id="parentDiv">
        @if (count($chats) > 0)
            <div class="chat-coversation-load text-center">
                <button class="btn btn-link load-more-btn" data-first="{{ $chats->last()->id }}" type="button">{{ translate('Load More') }}</button>
            </div>
        @endif
        <div class="chat-list px-4" id="chat-messages">
            @include('frontend.member.messages.messages_part',['chats' => $chats])
        </div>
    </div>
    <div class="chat-footer border-top p-3 attached-bottom bg-white">
        <form id="send-mesaage">
            <div class="input-group">
                <input type="hidden" id="chat_thread_id" name="chat_thread_id" value="{{ $chat_thread->id }}">
                <input type="text" class="form-control" name="message" id="message" placeholder="Your Message.." autocomplete="off">
                <input type="hidden" class="" name="attachment" id="attachment">
                <div class="input-group-append">
                    <button class="btn btn-circle btn-icon chat-attachment" type="button">
                        <i class="las la-paperclip"></i>
                    </button>
                    <button class="btn btn-primary btn-circle btn-icon" onclick="send_reply()" type="button">
                        <i class="las la-paper-plane"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="chat-info-wrap">
        <div class="overlay dark c-pointer" data-toggle="class-toggle" data-target=".chat-info-wrap" data-same=".chat-info"></div>
          <div class="chat-info c-scrollbar-light p-4 z-1">
                <div class="px-4 text-center mb-3">
                    <span class="avatar avatar-md mb-3">
                        @if ($chat_thread->$user_to_show->photo != null)
                            <img src="{{ uploaded_asset($chat_thread->$user_to_show->photo) }}">
                        @else
                            <img src="{{ static_asset('assets/frontend/default/img/avatar-place.png') }}">
                        @endif
                    </span>
                    <h4 class="h5 mb-2 fw-600">{{ $chat_thread->$user_to_show->first_name.' '.$chat_thread->$user_to_show->last_name }}</h4>
                </div>
                <div class="text-center">
                    <h6 class="fs-13">{{ translate('Age') }}: {{ \Carbon\Carbon::parse($chat_thread->$user_to_show->member->birthday)->age }}</h6>
                    <h6 class="fs-13">
                        {{ translate('Height') }} :
                        @if(!empty( $chat_thread->$user_to_show->physical_attributes->height))
                            {{ $chat_thread->$user_to_show->physical_attributes->height }}
                        @endif
                    </h6>
                    @if(get_setting('member_spiritual_and_social_background_section') == 'on')
                        <h6 class="fs-13">
                            {{ translate('Religion') }} :
                          @if(!empty($chat_thread->$user_to_show->spiritual_backgrounds->religion_id))
                              {{ $chat_thread->$user_to_show->spiritual_backgrounds->religion->name }}
                          @endif
                        </h6>
                    @endif
                    @if(get_setting('member_present_address_section') == 'on')
                    <h6 class="fs-13">
                        {{ translate('Location') }} :
                      @php
                          $present_address = \App\Models\Address::where('type','present')->where('user_id', $chat_thread->$user_to_show->id)->first();
                      @endphp
                      @if(!empty($present_address->country_id))
                          {{ $present_address->country->name }}
                      @endif
                    </h6>
                    @endif
                    @if(get_setting('member_language_section') == 'on')
                        <h6 class="fs-13">
                            {{ translate('Mother Tongue') }} :
                          @if($chat_thread->$user_to_show->member->mothere_tongue != null)
                              {{ \App\Models\MemberLanguage::where('id',$chat_thread->$user_to_show->member->mothere_tongue)->first()->name }}
                          @endif
                        </h6>
                    @endif

                    <div class="text-center mb-3 px-3 mt-3">
                        <a
                            @if(get_setting('full_profile_show_according_to_membership') == 1 && Auth::user()->membership == 1)
                                href="javascript:void(0);" onclick="package_update_alert()"
                            @else
                                href="{{ route('member_profile', $chat_thread->$user_to_show->id) }}"
                            @endif
                            class="btn btn-block btn-soft-primary">{{ translate('View Full Profile') }}
                        </a>
                    </div>
                </div>
            </div>
    </div>
</div>
