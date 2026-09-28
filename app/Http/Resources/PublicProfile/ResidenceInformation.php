<?php

namespace App\Http\Resources\PublicProfile;

use App\Models\Country;
use Illuminate\Http\Resources\Json\JsonResource;

class ResidenceInformation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $birth_country = $this->birth_country_id ? Country::where('id', $this->birth_country_id)->first()->name : null;
        $recidency_country = $this->recidency_country_id ? Country::where('id', $this->recidency_country_id)->first()->name : null;
        $growup_country = $this->growup_country_id ? Country::where('id', $this->growup_country_id)->first()->name : null;
        return [
            'birth_country' => $birth_country,
            'recidency_country' => $recidency_country,
            'growup_country' => $growup_country,
            'immigration_status' => $this->immigration_status,
        ];
    }
}
