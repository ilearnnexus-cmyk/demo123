<?php

namespace App\Http\Resources;

use App\Models\ExpressInterest;
use App\Models\ReportedUser;
use App\Models\Shortlist;
use App\Models\ViewGalleryImage;
use App\Models\ViewProfilePicture;
use Carbon\Carbon;
use App\Utility\MemberUtility;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->member != null) {
            $profile_view_resquest_status = ViewProfilePicture::where('user_id', $this->id)->where('requested_by', auth()->id())->where('status', 1)->first();
            $gallery_view_resquest_status = ViewGalleryImage::where('user_id', $this->id)->where('requested_by', auth()->id())->where('status', 1)->first();
            $avatar_image = $this->member->gender == 1 ? 'assets/img/avatar-place.png' : 'assets/img/female-avatar-place.png';
            $profile_picture_show = show_profile_picture($this);
            $package_update_alert = get_setting('full_profile_show_according_to_membership') == 1 && (auth()->check() && auth()->user()->membership == 1) ? true : false;
            $shortlist = Shortlist::where('user_id', $this->id)->where('shortlisted_by', auth()->id())->first();
            $do_interest = ExpressInterest::where('user_id', $this->id)->where('interested_by', auth()->id())->first();
            $received_interest = ExpressInterest::where('user_id', auth()->id())->where('interested_by', $this->id)->first();
            $profile_reported = ReportedUser::where('user_id', $this->id)->where('reported_by', auth()->id())->first();
            return [
                'user_id'              => $this->id,
                'code'                 => $this->code,
                'membership'           => $this->membership,
                'name'                 => $this->first_name . ' ' . $this->last_name,
                'photo'                => $profile_picture_show ? uploaded_asset($this->photo) : static_asset($avatar_image),
                'age'                  => !empty($this->member->birthday) ? Carbon::parse($this->member->birthday)->age : '',
                'country'              => MemberUtility::member_country($this->id),
                'height'               => !empty($this->physical_attributes->height) ? $this->physical_attributes->height : '',
                'package_update_alert' => $package_update_alert,
                'interest_status'      => ($do_interest ? 'sent interest' : $received_interest) ? 'received interest' : 'no interest',
                // 'interest_status'      => MemberUtility::member_interest_info($this->id)['interest_status'],
                // 'interest_text'        => MemberUtility::member_interest_info($this->id)['interest_text'],
                'shortlist_status'     => $shortlist ? 1 : 0,
                // 'shortlist_status'     => MemberUtility::member_shortlist_info($this->id)['shortlist_status'],
                // 'shortlist_text'       => MemberUtility::member_shortlist_info($this->id)['shortlist_text'],
                'report_status'        => $profile_reported ? true : false,
                'profile_view_resquest_status'   => $profile_view_resquest_status ? true : false,
                'gallery_view_resquest_status'   => $gallery_view_resquest_status ? true : false,
            ];
        }
    }
}
