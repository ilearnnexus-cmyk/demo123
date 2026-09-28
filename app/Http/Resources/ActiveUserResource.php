<?php

namespace App\Http\Resources;

use App\Models\ViewGalleryImage;
use App\Models\ViewProfilePicture;
use App\Utility\MemberUtility;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ActiveUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $avatar_image = $this->member->gender == 1 ? 'assets/img/avatar-place.png' : 'assets/img/female-avatar-place.png';
        $profile_picture_show = show_profile_picture($this);
        $package_update_alert = get_setting('full_profile_show_according_to_membership') == 1 && auth()->user()->membership == 1 ? true : false;

        return [
            'user_id'              => $this->id,
            'code'                 => $this->code,
            'membership'           => $this->membership,
            'first_name'           => $this->first_name,
            'last_name'            => $this->last_name,
            'photo'                => $profile_picture_show ? uploaded_asset($this->photo) : static_asset($avatar_image),
            'age'                  => !empty($this->member->birthday) ? Carbon::parse($this->member->birthday)->age : '',
            'country'              => MemberUtility::member_country($this->id),
            'height'               => !empty($this->physical_attributes->height) ? $this->physical_attributes->height : '',
            'religion'             => MemberUtility::member_religion($this->id),
            'mothere_tongue'       => MemberUtility::member_mothere_tongue($this->id),
            'marital_status'       => !empty($this->member->marital_status->name) ? $this->member->marital_status->name : '',
            'caste'                => !empty($this->spiritual_backgrounds->caste->name) ? $this->spiritual_backgrounds->caste->name . ', ' : "",
            'package_update_alert' => $package_update_alert,
            'interest_status'      => MemberUtility::member_interest_info($this->id)['interest_status'],
            'interest_text'        => MemberUtility::member_interest_info($this->id)['interest_text'],
            'shortlist_status'     => MemberUtility::member_shortlist_info($this->id)['shortlist_status'],
            'shortlist_text'       => MemberUtility::member_shortlist_info($this->id)['shortlist_text'],
            'report_status'        => MemberUtility::member_report_status($this->id) ? true : false,
            'profile_view_resquest_status' =>  ViewProfilePicture::where('user_id', $this->id)->where('requested_by', auth()->id())->where('status', 1)->first() ? true : false,
            'gallery_view_resquest_status' =>  ViewGalleryImage::where('user_id', $this->id)->where('requested_by', auth()->id())->where('status', 1)->first() ? true : false,
        ];
    }
}
