<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Profile\CasteResource;
use App\Http\Resources\Profile\CityResource;
use App\Http\Resources\Profile\CountryResource;
use App\Http\Resources\Profile\FamilyValuesResource;
use App\Http\Resources\Profile\LanguageResource;
use App\Http\Resources\Profile\MaritialStatusResource;
use App\Http\Resources\Profile\OnBehalfResource;
use App\Http\Resources\Profile\ReligionResource;
use App\Http\Resources\Profile\StateResource;
use App\Http\Resources\Profile\SubCasteResource;
use App\Models\Caste;
use App\Models\City;
use App\Models\Country;
use App\Models\FamilyValue;
use App\Models\Language;
use App\Models\MaritalStatus;
use App\Models\MemberLanguage;
use App\Models\OnBehalf;
use App\Models\Religion;
use App\Models\State;
use App\Models\SubCaste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class ProfileDropdownController extends Controller
{
    public function profile_dropdown(){
        $data['onbehalf_list'] = OnBehalfResource::collection(OnBehalf::latest()->get());
        $data['maritial_status'] = MaritialStatusResource::collection(MaritalStatus::latest()->get());
        $data['language_list'] = LanguageResource::collection(MemberLanguage::all());
        $data['religion_list'] = ReligionResource::collection(Religion::all());
        $data['family_value_list'] = FamilyValuesResource::collection(FamilyValue::all());
        $data['country_list'] = CountryResource::collection(Country::where('status',1)->get());
        return $this->response_data($data);
    }
    public function onbehalf_list(){
        return OnBehalfResource::collection(OnBehalf::latest()->get());
    }

    public function maritial_status(){
        return MaritialStatusResource::collection(MaritalStatus::latest()->get());
    }
    public function country_list(){
        return CountryResource::collection(Country::where('status',1)->get());
    }
    public function state_list($id){
        return StateResource::collection(State::where('country_id',$id)->get());
    }
    public function city_list($id){
        return CityResource::collection(City::where('state_id',$id)->get());
    }
    public function language_list(){
        return LanguageResource::collection(MemberLanguage::all());
    }
    public function religion_list(){
        return ReligionResource::collection(Religion::all());
    }
    public function caste_list($id){
        return CasteResource::collection(Caste::where('religion_id',$id)->get());
    }
    public function sub_caste_list($id){
        return SubCasteResource::collection(SubCaste::where('caste_id',$id)->get());
    }
    public function family_value_list(){
        return FamilyValuesResource::collection(FamilyValue::all());
    }
}
