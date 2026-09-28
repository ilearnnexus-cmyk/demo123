<?php

namespace App\Http\Resources;

use App\Models\IgnoredUser;
use App\Models\Member;
use App\Models\Package;
use App\Models\ProfileMatch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatThreadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->receiver != null && $this->sender != null) {
            $user_to_show = auth()->id() == $this->sender->id ? 'receiver' : 'sender';
            $member = Member::where('user_id', $this->$user_to_show->id)->first();
            $member_package = Package::find($member->current_package_id);
            return [
                'id' => $this->id,
                'user_id' => $this->$user_to_show->id,
                'active' => $this->active,
                'blocked_by_user' => $this->blocked_by_user,
                'unseen_message_count' => $this->chats->where('seen', 0)->count(),
                'member_photo' => $this->$user_to_show->photo != null ? uploaded_asset($this->$user_to_show->photo) : static_asset('assets/frontend/default/img/avatar-place.png'),
                'member_name' => $this->$user_to_show->first_name . ' ' . $this->$user_to_show->last_name,
                'last_message_time' => $this->chats->last() != null ? Carbon::parse($this->chats->last()->created_at)->diffForHumans() : '',
                'last_message' => $this->chats->last() ? $this->chats->last()->message  : '',
                'member_package' => $member_package ? new PackageResource($member_package) : '',
            ];
        }
    }
}
