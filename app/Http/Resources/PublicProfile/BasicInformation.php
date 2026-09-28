<?php

namespace App\Http\Resources\PublicProfile;

use App\Http\Resources\Profile\OnBehalfResource;
use App\Models\OnBehalf;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;



class BasicInformation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $age = Carbon::parse($this->member->birthday)->age;
        return [
            'firs_name' => $this->first_name,
            'last_name' => $this->last_name,
            'age' => $age,
            'religion' => $this->spiritual_backgrounds->religion->name ?? '',
            'caste' => $this->spiritual_backgrounds->caste->name ?? '',
            'date_of_birth' => $this->member->birthday,
            'onbehalf' => new OnBehalfResource(OnBehalf::find($this->member->on_behalves_id)),
            'no_of_children' => $this->member->children ?? '',
            'gender' => $this->member->gender == 1 ? "Male" : "Female",
            'phone' => $this->phone ?? "",
            'maritial_status' =>  $this->member->marital_status ? $this->member->marital_status->name : '',
            'photo' => uploaded_asset($this->photo) ?? static_asset('assets/img/avatar-place.png'),

        ];
    }
}
