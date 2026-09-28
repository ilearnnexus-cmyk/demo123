<?php

namespace App\Http\Resources\PublicProfile;

use App\Models\Caste;
use App\Models\FamilyValue;
use App\Models\Religion;
use App\Models\SubCaste;
use Illuminate\Http\Resources\Json\JsonResource;

class SpiritualSocialBackground extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $religion = Religion::find($this->religion_id);
        $caste = Caste::find($this->caste_id);
        $sub_caste = SubCaste::find($this->sub_caste_id);
        $family_value = FamilyValue::find($this->family_value_id);
        return [
            'religion_id'=> $religion ? $religion->name :'',
            'caste_id'=>$caste ? $caste->name :'',
            'sub_caste_id'=>$sub_caste ? $sub_caste->name :'',
            'ethnicity'=>$this->ethnicity,
            'personal_value'=>$this->personal_value,
            'family_value_id'=> $family_value ? $family_value->name :'',
            'community_value'=>$this->community_value,
        ];
    }
}
