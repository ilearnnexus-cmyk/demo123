<?php

namespace App\Http\Resources\PublicProfile;

use App\Models\Caste;
use App\Models\Country;
use App\Models\FamilyValue;
use App\Models\Language;
use App\Models\MaritalStatus;
use App\Models\Religion;
use App\Models\State;
use App\Models\SubCaste;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerExpectationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $residence_country = Country::where('id', $this->residence_country_id)->first();
        $preferred_country = Country::where('id', $this->preferred_country_id)->first();
        $preferred_state = State::where('id', $this->preferred_state_id)->first();
        $religion = Religion::find($this->religion_id);
        $caste = Caste::find($this->caste_id);
        $sub_caste = SubCaste::find($this->sub_caste_id);
        $family_value = FamilyValue::find($this->family_value_id);
        $marital_status = MaritalStatus::find($this->marital_status_id);
        $language = Language::find($this->language_id);
        return [
            'general' => $this->general,
            'height' => $this->height,
            'weight' => $this->weight,
            'marital_status' => $marital_status ? $marital_status->name : null,
            'children_acceptable' => attribute_text_format($this->children_acceptable),
            'residence_country_id' => $residence_country ? $residence_country->name : '',
            'religion_id' =>  $religion ?  $religion->name : '',
            'caste_id' =>  $caste ?  $caste->name : '',
            'sub_caste_id' => $sub_caste ? $sub_caste->name : '',
            'education' => $this->education,
            'profession' => $this->profession,
            'smoking_acceptable' => attribute_text_format($this->smoking_acceptable),
            'drinking_acceptable' => attribute_text_format($this->drinking_acceptable),
            'diet' => attribute_text_format($this->diet),
            'body_type' => $this->body_type,
            'personal_value' => $this->personal_value,
            'manglik' => attribute_text_format($this->manglik),
            'language' => $language ? $language->name : null,
            'family_value_id' => $family_value ? $family_value->name : '',
            'preferred_country_id' => $preferred_country ? $preferred_country->name : '',
            'preferred_state_id' => $preferred_state ? $preferred_state->name : '',
            'complexion' => $this->complexion,
        ];
    }
}
