<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralEarningsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $referral_user = User::find($this->referral_user);
        return [
            'name'   => $referral_user ? $referral_user->first_name.' '.$referral_user->last_name : '',
            'amount' => single_price($this->amount),
            'date'   => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}
