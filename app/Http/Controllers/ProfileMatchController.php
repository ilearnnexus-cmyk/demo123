<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfileMatch;
use App\Models\Member;
use App\Models\PhysicalAttribute;
use App\Models\SpiritualBackground;
use App\Models\Education;
use App\Models\Career;
use App\Models\Address;
use App\Models\Lifestyle;
use App\Models\User;

class ProfileMatchController extends Controller
{

    public function match_profiles($user = '')
    {
      if(empty($user))
      {
        $users = User::where('user_type','member')
                        ->where('blocked', 0)
                        ->where('approved', 1)
                        ->where('deactivated',0)->get();
      }
      else {
        $users = User::where('id', $user)->get();
      }

      foreach ($users as $user) {
        $expectation_attributes = 0;
        $matches_attributes     = 0;
        $match_percentage       = 0;

        $expected_residence_country  = !empty($user->partner_expectations->residence_country_id) ? $user->partner_expectations->residence_country_id : "";
        $expected_min_height         = !empty($user->partner_expectations->height) ? $user->partner_expectations->height : "";
        $expected_max_weight         = !empty($user->partner_expectations->weight) ? $user->partner_expectations->weight : "";
        $expected_marital_status     = !empty($user->partner_expectations->marital_status_id) ? $user->partner_expectations->marital_status_id : "";
        $expected_language           = !empty($user->partner_expectations->language_id) ? $user->partner_expectations->language_id : "";

        $expected_religion           = !empty($user->partner_expectations->religion) ? $user->partner_expectations->religion : "";
        $expected_caste              = !empty($user->partner_expectations->caste_id) ? $user->partner_expectations->caste_id : "";
        $expected_sub_caste_id       = !empty($user->partner_expectations->sub_caste_id) ? $user->partner_expectations->sub_caste_id : "";

        $expected_education          = !empty($user->partner_expectations->education) ? $user->partner_expectations->education : "";
        $expected_profession         = !empty($user->partner_expectations->profession) ? $user->partner_expectations->profession : "";

        $expected_family_value       = !empty($user->partner_expectations->family_value_id) ? $user->partner_expectations->family_value_id : "";
        $expected_smoking_condition  = !empty($user->partner_expectations->smoking_acceptable) ? $user->partner_expectations->smoking_acceptable : "";
        $expected_drinking_condition = !empty($user->partner_expectations->drinking_acceptable) ? $user->partner_expectations->drinking_acceptable : "";
        $expected_diet_condition     = !empty($user->partner_expectations->diet) ? $user->partner_expectations->diet : "";

        $expected_state           = !empty($user->partner_expectations->preferred_state_id) ? $user->partner_expectations->preferred_state_id : "";
        $expected_country         = !empty($user->partner_expectations->preferred_country_id) ? $user->partner_expectations->preferred_country_id : "";

        $partners = User::where('user_type','member')
                        ->where('id', '!=', $user->id)
                        ->where('blocked', 0)
                        ->where('approved', 1)
                        ->where('deactivated',0)->get();
        $user_ids = Member::where('gender', '!=', $user->member->gender)->pluck('user_id')->toArray();
        if(count($user_ids) >0){
            $partners = $partners->WhereIn('id', $user_ids);
        }

        foreach ($partners as $partner) {

          if(!empty($expected_residence_country)) {
              $expectation_attributes++;
              if(Address::where('user_id', $partner->id)->where('country_id', $expected_residence_country)->where('type', 'present')->count() > 0){
                $matches_attributes++;
              }
          }

          if(!empty($expected_min_height)) {
              $expectation_attributes++;
              if(PhysicalAttribute::where('user_id', $partner->id)->where('height', '>=', $expected_min_height)->count() > 0 ){
                $matches_attributes++;
              }
          }

          if(!empty($expected_max_weight)) {
              $expectation_attributes++;
              if(PhysicalAttribute::where('user_id', $partner->id)->where('weight', '<=', $expected_max_weight)->count() > 0){
                $matches_attributes++;
              }
          }

          if(!empty($expected_marital_status)){
              $expectation_attributes++;
              if(Member::where('user_id', $partner->id)->where('marital_status_id', $expected_marital_status)->count() > 0){
                $matches_attributes++;
              }
          }

          // Match by religion religion
          $match_religion_cast = 'no_need_check';
          if(!empty($expected_sub_caste_id)){
              if(SpiritualBackground::where('user_id', $partner->id)->where('sub_caste_id', $expected_sub_caste_id)->count() > 0 ){
                $expectation_attributes+=3;
                $matches_attributes+= 3;
              }
              else {
                $expectation_attributes++;
                $match_religion_cast = 'need_check';
              }
          }

          if($match_religion_cast == 'need_check' && !empty($expected_caste)) {
              if(SpiritualBackground::where('user_id', $partner->id)->where('caste_id', $expected_caste)->count() > 0 ){
                $expectation_attributes+=2;
                $matches_attributes+=2;
                $match_religion_cast = 'no_need_check';
              }
              else {
                $expectation_attributes++;
                $match_religion_cast = 'need_check';
              }
          }

          if($match_religion_cast == 'need_check' && !empty($expected_religion)) {
              $expectation_attributes++;
              if(SpiritualBackground::where('user_id', $partner->id)->where('religion_id', $expected_religion)->count() > 0){
                $matches_attributes++;
              }
          }
          $match_religion = 'no_need_check';

          if(!empty($expected_language)){
              $expectation_attributes++;
              if(Member::where('user_id', $partner->id)->where('mothere_tongue', $expected_language)->count() > 0){
                $matches_attributes++;
              }
          }

          if(!empty($expected_education)){
              $expectation_attributes++;
              if(Education::where('user_id', $partner->id)->where('degree', 'like', '%'.$expected_education.'%')->where('present',1)->count() > 0 ){
                $matches_attributes++;
              }
          }

          if(!empty($expected_profession)){
              $expectation_attributes++;
              if(Career::where('user_id', $partner->id)->where('designation', 'like', '%'.$expected_profession.'%')->where('present',1)->count() > 0 ){
                $matches_attributes++;
              }
          }

          if(!empty($expected_family_value)){
            $expectation_attributes++;
            if(SpiritualBackground::where('user_id', $partner->id)->where('family_value_id', $expected_family_value)->count() > 0 ){
              $matches_attributes++;
            }
          }

          if(!empty($expected_smoking_condition)){
              $expectation_attributes++;
              if($expected_smoking_condition == "dose_not_matter"){
                $matches_attributes++;
              }
              elseif(Lifestyle::where('user_id', $partner->id)->where('smoke',$expected_smoking_condition)->count() > 0 ){
                $matches_attributes++;
              }
          }

          if(!empty($expected_drinking_condition)){
              $expectation_attributes++;
              if($expected_drinking_condition == "dose_not_matter"){
                $matches_attributes++;
              }
              elseif(Lifestyle::where('user_id', $partner->id)->where('drink',$expected_drinking_condition)->count() > 0 ){
                $matches_attributes++;
              }
          }

          if(!empty($expected_diet_condition)){
              $expectation_attributes++;
              if($expected_diet_condition == "dose_not_matter"){
                $matches_attributes++;
              }
              elseif(Lifestyle::where('user_id', $partner->id)->where('diet',$expected_diet_condition)->count() > 0 ){
                $matches_attributes++;
              }
          }

          // match Preferred Country
          $expected_country_check = "no_need_check";
          if(!empty($expected_state)) {
              if(Address::where('user_id', $partner->id)->where('state_id', $expected_state)->where('type', 'permanent')->count() > 0){
                $expectation_attributes+=2;
                $matches_attributes+=2;
              }
              else {
                $expectation_attributes++;
                $expected_country_check = 'need_check';
              }
          }

          if($expected_country_check == 'need_check' && !empty($expected_country)) {
              $expectation_attributes++;
              if(Address::where('user_id', $partner->id)->where('country_id', $expected_state)->where('type', 'permanent')->count() > 0){
                $matches_attributes++;
              }
          }
          $expected_country_check = 'no_need_check';
          // match Preferred Country $end

          if( $expectation_attributes > 0 && $matches_attributes){
            $match_percentage = round(($matches_attributes/$expectation_attributes)*100);
          }

          $profile_match = ProfileMatch::where('user_id',$user->id)->where('match_id',$partner->id)->first();
          if(empty($profile_match)){
              $profile_match                    = new ProfileMatch;
              $profile_match->user_id           = $user->id;
              $profile_match->match_id          = $partner->id;
          }
          $profile_match->match_percentage  = $match_percentage;
          $profile_match->save();
        }
      }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function migrate_profiles(Request $request){
    }
}
