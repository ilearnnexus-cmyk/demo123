<?php

namespace App\Utility;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationUtility
{
    public static function set_notification($type, $message = '', $link = '/', $sender = false, $receiver = 0, $showing_panel = null)
    {
        try {
            $notification                       = new Notification;
            $notification->notification_type    = $type;
            $notification->sender_id            = $sender ? $sender : (Auth::check() ? Auth::user()->id : 0);
            $notification->receiver_id          = $receiver;
            $notification->message              = translate($message);
            $notification->link                 = $link;
            $notification->seen_by_receiver     = 0;
            $notification->showing_panel        = $showing_panel;
            $notification->save();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

    }

    public static function get_my_notifications($limit = 0, $only_unseen = true, $only_count = false, $paginated = false)
    {
        $list = array();
        $count = 0;

        if (!Auth::check() && !$only_count) {
            return $list;
        } elseif (!Auth::check() && $only_count) {
            return $count;
        }

        $panel = '';
        if (Auth::user()->user_type == 'member') {
            $panel = 'member';
        }
        else{
            $panel = 'admin';
        }


        $notifications_query = Notification::where('receiver_id', Auth::user()->id)->latest();

        if ($panel == 'admin') {
            $notifications_query->orWhere('showing_panel', $panel);
        }
        if ($only_unseen == true) {
            $notifications_query->where('seen_by_receiver', 0);
        }

        //return only the numbers of notifications
        if ($only_count) {
            return $notifications_query->count();
        } else if ($paginated) {
            //return paginated data for all notifications page
            return $notifications_query->paginate($limit);
        }
        $notifications = $notifications_query->limit($limit)->get();

        foreach ($notifications as $notification) {
            if($notification->sender != null){
                $item                           = array();
                $item['notification_id']        = $notification->id;
                $item['notification_type']      = $notification->notification_type;
                $item['message']                = $notification->message;
                $item['link']                   = url($notification->link);
                $item['sender_name']            = $notification->sender->first_name.' '. $notification->sender->last_name;
                $item['sender_photo']           = $notification->sender->photo > 0 ? uploaded_asset($notification->sender->photo) : static_asset('assets/img/avatar-place.png');
                $item['seen']                   = $notification->seen_by_receiver == 1 ? true : false;
                $item['date']                   = Carbon::parse($notification->created_at)->diffForHumans();
                $item['express_interest_id']    = $notification->express_interest_id;
                $item['express_interest_status']= $notification->express_interest_status;

                $list[] = $item;
            }
        }

        return $list;
    }

}
