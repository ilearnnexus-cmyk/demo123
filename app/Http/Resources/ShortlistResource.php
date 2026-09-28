<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Utility\MemberUtility;
use App\Models\ExpressInterest;
use App\Models\ReportedUser;
use App\Models\Shortlist;
use App\Models\ViewGalleryImage;
use App\Models\ViewProfilePicture;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ShortlistResource extends JsonResource
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
            $profile_view_resquest_status = ViewProfilePicture::where('user_id', $this->user_id)->where('requested_by', auth()->id())->where('status', 1)->first();
            $gallery_view_resquest_status = ViewGalleryImage::where('user_id', $this->user_id)->where('requested_by', auth()->id())->where('status', 1)->first();
            $shortlist = Shortlist::where('user_id', $this->user_id)->where('shortlisted_by', auth()->id())->first();
            $avatar_image = $user->member->gender == 1 ? 'assets/img/avatar-place.png' : 'assets/img/female-avatar-place.png';
            $profile_picture_show = show_profile_picture($this->user);
            $package_update_alert = get_setting('full_profile_show_according_to_membership') == 1 && auth()->user()->membership == 1 ? true : false;
            $do_interest = ExpressInterest::where('user_id', $this->user_id)->where('interested_by', auth()->id())->first();
            $received_interest = ExpressInterest::where('user_id', auth()->id())->where('interested_by', $this->user_id)->first();
            $interest = ExpressInterest::where('user_id', $this->user_id)->where('interested_by', auth()->user()->id)->first();
            $profile_reported = ReportedUser::where('user_id', $this->user_id)->where('reported_by', auth()->id())->first();

            return [
                'user_id'              => $this->user_id,
                'package_update_alert' => $package_update_alert,
                'photo'                => $profile_picture_show ? uploaded_asset($this->user->photo) : static_asset($avatar_image),
                'name'                 => $this->user->first_name . ' ' . $this->user->last_name,
                'age'                  => Carbon::parse($this->user->member->birthday)->age,
                'religion'             => MemberUtility::member_religion($this->user_id),
                'country'              => MemberUtility::member_country($this->user_id),
                'membership'           => $this->user->membership,
                'code'                 => $this->user->code,
                'height'               => $this->user->physical_attributes->height ??  '',
                'mothere_tongue'       => MemberUtility::member_mothere_tongue($this->user_id),
                'express_interest'     => $interest ? true : false,
                'interest_status'      => ($do_interest ? 'sent interest' : $received_interest) ? 'received interest' : 'no interest',
                'shortlist_status'     => $shortlist ? 1 : 0,
                'report_status'        => $profile_reported ? true : false,
                'profile_view_resquest_status'   => $profile_view_resquest_status ? true : false,
                'gallery_view_resquest_status'   => $gallery_view_resquest_status ? true : false,
            ];
        }
    }
}
