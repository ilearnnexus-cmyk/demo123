<?php

namespace App\Http\Resources;

use App\Models\ViewProfilePicture;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ProfileImageRequest extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $view_profile_images = ViewProfilePicture::find($this->id);
        $user = User::where('id', $view_profile_images->requested_by)->first();
        if ($user != null) {
            $birth_date = $user->member->birthday;
            return [
                'id' => $this->id,
                'photo' => uploaded_asset($user->photo) ?? static_asset('assets/img/avatar-place.png'),
                'name' => $user->first_name . $user->last_name,
                'date_of_birth' => Carbon::parse($birth_date)->age,
                'status' => $view_profile_images->status,
            ];
        }
    }
}
