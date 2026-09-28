<?php

namespace App\Http\Resources\PublicProfile;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Resources\Json\JsonResource;

class PresentAddress extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'country'=> Country::where('id', $this->country_id)->first()->name,
            'state'=> State::where('id', $this->state_id)->first()->name,
            'city'=> City::where('id', $this->city_id)->first()->name,
            'postal_code'=> $this->postal_code,
        ];
    }
}
