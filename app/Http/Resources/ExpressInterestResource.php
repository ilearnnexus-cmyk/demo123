<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use App\Utility\MemberUtility;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpressInterestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $interested_by = User::find($this->interested_by);
        if ($interested_by != null) {
            $package_update_alert = (get_setting('full_profile_show_according_to_membership') == 1 && auth()->user()->membership == 1) ? true : false;
            $default_image = $interested_by->member->gender == 1 ? static_asset('assets/img/avatar-place.png') : static_asset('assets/img/female-avatar-place.png');
            return [
                'id'                   => $this->id,
                'user_id'              => $this->interested_by,
                'package_update_alert' => $package_update_alert,
                'photo'                => uploaded_asset($interested_by->photo) ?? $default_image,
                'name'                 => $interested_by->first_name . ' ' . $interested_by->last_name,
                'age'                  => Carbon::parse($interested_by->member->birthday)->age,
                'status'               => $this->status == 1 ? 'Approved' : 'Pending',
                'religion'             => MemberUtility::member_religion($this->interested_by),
                'country'              => MemberUtility::member_country($this->interested_by),
                'mothere_tongue'       => MemberUtility::member_mothere_tongue($this->interested_by),
            ];
        }
    }
}
