<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use App\Utility\MemberUtility;
use Illuminate\Http\Resources\Json\JsonResource;

class IgnoredUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::find($this->user_id);
        if ($user != null) {
        $avatar_image = $user->member->gender == 1 ? 'assets/img/avatar-place.png' : 'assets/img/female-avatar-place.png';
        $profile_picture_show = show_profile_picture($this->user);
        return [
            'user_id'        => $this->user_id,
            'photo'          => $profile_picture_show ? uploaded_asset($this->user->photo) : static_asset($avatar_image),
            'name'           => $this->user->first_name.' '.$this->user->last_name,
            'age'            => Carbon::parse($this->user->member->birthday)->age,
            'religion'       => MemberUtility::member_religion($this->user_id),
            'country'        => MemberUtility::member_country($this->user_id),
            'mothere_tongue' => MemberUtility::member_mothere_tongue($this->user_id),
        ];
    }
    }
}
