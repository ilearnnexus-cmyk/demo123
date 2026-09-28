<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\DbStoreNotification;
use Notification;
use App\Utility\EmailUtility;
use App\Models\ViewGalleryImage;
use App\Utility\SmsUtility;
use App\Models\Member;
use App\Models\User;
use Auth;
use DB;
use Kutia\Larafirebase\Facades\Larafirebase;

class ViewGalleryImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $my_gallery_image_view_requests = DB::table('view_gallery_images')
            ->orderBy('id', 'desc')
            ->where('user_id', Auth::user()->id)
            ->join('users', 'view_gallery_images.user_id', '=', 'users.id')
            ->select('view_gallery_images.id')
            ->distinct()
            ->paginate(10);
        return view('frontend.member.my_gallery_image_view_requests', compact('my_gallery_image_view_requests'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth_user = Auth::user();
        $view_gallert_image                = new ViewGalleryImage;
        $view_gallert_image->user_id       = $request->id;
        $view_gallert_image->requested_by  = $auth_user->id;
        if ($view_gallert_image->save()) {
            $member = Member::where('user_id', $auth_user->id)->first();
            $member->remaining_gallery_image_view = $member->remaining_gallery_image_view - 1;
            $member->save();

            $notify_user = User::where('id', $request->id)->first();

            // View Profile Picture Store Notification for member
            try {
                $notify_type   = 'gallery_image_view';
                $id            = unique_notify_id();
                $notify_by     = $auth_user->id;
                $info_id       = $view_gallert_image->id;
                $message       = $auth_user->first_name . ' ' . $auth_user->last_name . ' ' . translate(' wants to see your gallery images.');
                $route         = 'gallery-image-view-request.index';

                // fcm 
                if (get_setting('firebase_push_notification') == 1) {
                $fcmTokens = User::where('id', $request->id)->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
                Larafirebase::withTitle($notify_type)
                    ->withBody($message)
                    ->sendMessage($fcmTokens);
                }
                // end of fcm

                Notification::send($notify_user, new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
            } catch (\Exception $e) {
                // dd($e);
            }

            // View Profile Picture email send to member
            if ($notify_user->email != null && get_email_template('gallery_image_view_request_email', 'status')) {
                EmailUtility::email_on_request($notify_user, 'gallery_image_view_request_email');
            }

            // View Profile Picture email SMS to member
            if ($notify_user->phone != null && addon_activation('otp_system') && (get_sms_template('gallery_image_view_request', 'status') == 1)) {
                SmsUtility::sms_on_request($notify_user, 'gallery_image_view_request');
            }

            return 1;
        } else {
            return 0;
        }
    }

    public function accept_request(Request $request)
    {
        $auth_user = Auth::user();
        $view_gallery_image = ViewGalleryImage::findOrFail($request->gallery_image_view_request_id);
        //   dd($view_gallery_image);
        $view_gallery_image->status = 1;
        if ($view_gallery_image->save()) {

            $notify_user = User::where('id', $view_gallery_image->requested_by)->first();

            // Express Interest Store Notification for member
            try {
                $notify_type = 'accept_gallery_image_view_request';
                $id = unique_notify_id();
                $notify_by = $auth_user->id;
                $info_id = $view_gallery_image->id;
                $message = $auth_user->first_name . ' ' . $auth_user->last_name . ' ' . translate(' has accepted your gallery image view request.');
                $route = route("member_profile", $auth_user->id);

                // fcm 
                if (get_setting('firebase_push_notification') == 1) {
                $fcmTokens = User::where('id', $view_gallery_image->requested_by)->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
                Larafirebase::withTitle($notify_type)
                    ->withBody($message)
                    ->sendMessage($fcmTokens);
                }
                // end of fcm

                Notification::send($notify_user, new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
            } catch (\Exception $e) {
                // dd($e);
            }

            // View Profile Picture email send to member
            if ($notify_user->email != null && get_email_template('gallery_image_view_request_accepted_email', 'status')) {
                EmailUtility::email_on_accept_request($notify_user, 'gallery_image_view_request_accepted_email');
            }

            // View Profile Picture email SMS to member
            if ($notify_user->phone != null && addon_activation('otp_system') && (get_sms_template('gallery_image_view_request_accepted', 'status') == 1)) {
                SmsUtility::sms_on_accept_request($notify_user, 'gallery_image_view_request_accepted');
            }

            flash(translate('Interest has been accepted successfully.'))->success();
            return redirect()->route('gallery-image-view-request.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    public function reject_request(Request $request)
    {
        $auth_user = Auth::user();
        $gallery_view_request = ViewGalleryImage::findOrFail($request->gallery_image_view_request_id);

        if (ViewGalleryImage::destroy($request->gallery_image_view_request_id)) {

            $notify_user = User::where('id', $gallery_view_request->requested_by)->first();
            try {
                $notify_type = 'reject_gallery_image_view_request';
                $id = unique_notify_id();
                $notify_by = Auth::user()->id;
                $info_id = $gallery_view_request->id;
                $message = $auth_user->first_name . ' ' . $auth_user->last_name . ' ' . translate(' has rejected your gallery image view request.');
                $route = route("member_profile", $auth_user->id);

                // fcm 
                if (get_setting('firebase_push_notification') == 1) {
                $fcmTokens = User::where('id', $gallery_view_request->requested_by)->whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
                Larafirebase::withTitle($notify_type)
                    ->withBody($message)
                    ->sendMessage($fcmTokens);
                }
                // end of fcm

                Notification::send($notify_user, new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
            } catch (\Exception $e) {
                // dd($e);
            }

            flash(translate('gallery image view request has been rejected successfully.'))->success();
            return redirect()->route('gallery-image-view-request.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }
}
