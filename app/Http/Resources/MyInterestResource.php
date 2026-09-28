<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Utility\MemberUtility;
use App\Models\ExpressInterest;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MyInterestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $interest = ExpressInterest::find($this->id);
        $user = User::find($interest->user_id);
        if ($user != null && $user->member) {
            $package_update_alert = get_setting('full_profile_show_according_to_membership') == 1 && auth()->user()->membership == 1 ? true : false;
            $avatar_image = $user->member->gender == 1 ? 'assets/img/avatar-place.png' : 'assets/img/female-avatar-place.png';
            $profile_picture_show = show_profile_picture($user);

            return [
                'user_id'              => $interest->user_id,
                'package_update_alert' => $package_update_alert,
                'photo'                => $profile_picture_show ? uploaded_asset($user->photo) : static_asset($avatar_image),
                'name'                 => $interest->user->first_name . ' ' . $interest->user->last_name,
                'age'                  => Carbon::parse($interest->user->member->birthday)->age,
                'religion'             => MemberUtility::member_religion($interest->user_id),
                'country'              => MemberUtility::member_country($interest->user_id),
                'mothere_tongue'       => MemberUtility::member_mothere_tongue($interest->user_id),
                'status'               => $interest->status == 1 ? 'Approved' : 'Pending',
            ];
        }
    }
}
