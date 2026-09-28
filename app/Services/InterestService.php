<?php

namespace App\Services;

use App\Models\User;
use App\Models\ChatThread;
use App\Utility\SmsUtility;
use App\Utility\EmailUtility;
use App\Models\ExpressInterest;
use App\Notifications\DbStoreNotification;
use Illuminate\Support\Facades\Notification;
use Kutia\Larafirebase\Facades\Larafirebase;

class InterestService
{

      public function store($user_id)
      {
            $interested_by_user = auth()->user();
            $interested_by_member = $interested_by_user->member;
            if ($interested_by_member->remaining_interest > 0) {
                  // Store express interest data
                  $express_interest                 = new ExpressInterest;
                  $express_interest->user_id        = $user_id;
                  $express_interest->interested_by  = $interested_by_user->id;
                  $express_interest->save();

                  // Deduct interested by user's remaining express interest value
                  $interested_by_member->remaining_interest -= 1;
                  $interested_by_member->save();

                  $notify_user = User::where('id', $user_id)->first();

                  $notify_type = 'express_interest';
                  $notify_by = $interested_by_user->id;
                  $info_id = $express_interest->id;
                  $message = $interested_by_user->first_name . ' ' . $interested_by_user->last_name . ' ' . translate(' has Expressed Interest On You.');
                  $route = route('interest_requests');

                  $this->notifyUser($user_id, $notify_user, $notify_type, $notify_by, $info_id, $message, $route);

                  // Express Interest email send to member
                  if ($notify_user->email != null && get_email_template('email_on_express_interest', 'status')) {
                        EmailUtility::email_on_request($notify_user, 'email_on_express_interest');
                  }

                  // Express Interest Send SMS to member
                  if ($notify_user->phone != null && addon_activation('otp_system') && (get_sms_template('express_interest', 'status') == 1)) {
                        SmsUtility::sms_on_request($notify_user, 'express_interest');
                  }

                  return true;
            } else {
                  return false;
            }
      }

      public function accept($interest_id)
      {
            $interest = ExpressInterest::find($interest_id);
            if ($interest) {
                  $interest->status = 1;
                  $interest->save();

                  $existing_chat_thread = ChatThread::where('sender_user_id', $interest->interested_by)->where('receiver_user_id', $interest->user_id)->first();
                  if ($existing_chat_thread == null) {
                        $chat_thread                    = new ChatThread;
                        $chat_thread->thread_code       = $interest->interested_by . date('Ymd') . $interest->user_id;
                        $chat_thread->sender_user_id    = $interest->interested_by;
                        $chat_thread->receiver_user_id  = $interest->user_id;
                        $chat_thread->save();
                  }

                  $notify_user = User::where('id', $interest->interested_by)->first();

                  $notify_type = 'accept_interest';
                  $notify_by = auth()->user()->id;
                  $info_id = $interest->id;
                  $message = auth()->user()->first_name . ' ' . auth()->user()->last_name . ' ' . translate(' has accepted your interest.');
                  $route = route('my_interests.index');

                  $this->notifyUser($interest->interested_by, $notify_user, $notify_type, $notify_by, $info_id, $message, $route);

                  // Express Interest email send to member
                  if ($notify_user->email != null && get_email_template('email_on_accepting_interest', 'status')) {
                        EmailUtility::email_on_accept_request($notify_user, 'email_on_accepting_interest');
                  }

                  // Express Interest Send SMS to member
                  if ($notify_user->phone != null && addon_activation('otp_system') && (get_sms_template('accept_interest', 'status') == 1)) {
                        SmsUtility::sms_on_accept_request($notify_user, 'accept_interest');
                  }

                  return true;
            }
            return false;
      }

      public function reject($interest_id)
      {
            $interest = ExpressInterest::find($interest_id);
            if ($interest) {
                  ExpressInterest::destroy($interest_id);

                  $notify_user = User::where('id', $interest->interested_by)->first();

                  $notify_type = 'reject_interest';
                  $notify_by = auth()->user()->id;
                  $info_id = $interest->id;
                  $message = auth()->user()->first_name . ' ' . auth()->user()->last_name . ' ' . translate(' has rejected your interest.');
                  $route = route('my_interests.index');

                  $this->notifyUser($interest->interested_by, $notify_user, $notify_type, $notify_by, $info_id, $message, $route);

                  return true;
            }
            return false;
      }

      public function notifyUser($user_id, $notify_user, $notify_type, $notify_by, $info_id, $message, $route)
      {
            try {
                  $id = unique_notify_id();

                  // fcm 
                  if (get_setting('firebase_push_notification') == 1) {
                        $fcmTokens = User::where('id', $user_id)->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
                        Larafirebase::withTitle($notify_type)
                              ->withBody($message)
                              ->sendMessage($fcmTokens);
                  }
                  // end of fcm

                  Notification::send($notify_user, new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
                  return true;
            } catch (\Exception $e) {
                  return false;
            }
      }
}
