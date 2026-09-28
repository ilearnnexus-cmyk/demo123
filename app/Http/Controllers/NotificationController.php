<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Auth;
use Str;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::latest()->where('notifiable_id',Auth()->user()->id)->paginate(10);
        return view('admin.notifications',compact('notifications'));
    }

    public function frontend_notify_listing()
    {
        $notifications = Notification::latest()->where('notifiable_id',Auth()->user()->id)->paginate(10);
        return view('frontend.member.notifications',compact('notifications'));
    }

    public function notification_view($id)
    {
        $notification = Notification::findOrFail($id);
        $notification_data = json_decode($notification->data);

        // Notification seen
        if($notification->read_at == null)
        {
            $notification->read_at = date('Y-m-d');
            $notification->save();
        }

        if($notification_data->type == 'member_registration' && !Str::contains($notification_data->route,'http'))
        {
            $membership = User::where('id',$notification_data->notify_by)->first()->membership;
            return redirect()->route($notification_data->route, $membership);
        }
        else {
            if(Str::contains($notification_data->route,'http')){
                return redirect($notification_data->route);
            }
            else{
                return redirect()->route($notification_data->route);
            }
        }

    }

    public function mark_all_as_read(){
        $notifications = Notification::where('notifiable_id',Auth::user()->id)->where('read_at',null)->get();
        foreach($notifications as $notification){
            $notification->read_at = date('Y-m-d');
            $notification->save();
        }
        flash('All notifications are marked as read.')->success();
        return back();
    }
}
