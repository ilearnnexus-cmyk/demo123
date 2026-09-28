<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use App\Models\ExpressInterest;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $check = true;
        $notify_data = json_decode($this->data);
        $user = User::find($notify_data->notify_by);

        if($notify_data->type == 'express_interest'){
            $interest_data = ExpressInterest::find($notify_data->info_id);
            $check = empty($interest_data) ? false : true;
        }

        $avatar_image = 'assets/img/avatar-place.png';
        $profile_picture_show = false;
        if ($user) {
            $avatar_image = $user->member->gender == 1 ? 'assets/img/avatar-place.png' : 'assets/img/female-avatar-place.png';
            $profile_picture_show = show_profile_picture($user);
        }
        
        return [
            'check'           => $check,
            'notification_id' => $this->id,
            'photo'           => $profile_picture_show ? uploaded_asset($user->photo) : static_asset($avatar_image),
            'message'         => $notify_data->message,
            'time'            => Carbon::parse($this->created_at)->diffForHumans(),
            'read_at'         => $this->read_at == null ? 'New' : 'read',
        ];
    }
}
