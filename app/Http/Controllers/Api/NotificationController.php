<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    public function notifications()
    {
        $notifications = Notification::latest()->where('notifiable_id', Auth()->user()->id)->paginate(10);
        return NotificationResource::collection($notifications)->additional([
            'result' => true
        ]);
    }

    public function mark_all_as_read()
    {
        $notifications = Notification::where('notifiable_id', Auth()->user()->id)->where('read_at', null)->get();
        foreach ($notifications as $notification) {
            $notification->read_at = date('Y-m-d');
            $notification->save();
        }
        return $this->success_message('All notifications are marked as read.');
    }
    public function single_notification_read($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read_at = date('Y-m-d');
        $notification->save();

        return $this->success_message(' notification is marked as read.');
    }
}
