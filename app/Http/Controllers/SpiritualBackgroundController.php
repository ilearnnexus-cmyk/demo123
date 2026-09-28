<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpiritualBackground;
use Validator;
use Redirect;

class SpiritualBackgroundController extends Controller
{
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
         $this->rules = [
             'member_religion_id'   => [ 'required','max:255'],
             'member_caste_id'      => [ 'required','max:255'],
             'ethnicity'            => [ 'max:255'],
             'personal_value'       => [ 'max:255'],
             'community_value'      => [ 'max:255'],
         ];
         $this->messages = [
             'member_religion_id.required'   => translate('Religion is required'),
             'member_religion_id.max'        => translate('Max 255 characters'),
             'member_caste_id.required'      => translate('Caste is required'),
             'member_caste_id.max'           => translate('Max 255 characters'),
             'ethnicity.max'                 => translate('Max 255 characters'),
             'personal_value.max'            => translate('Max 255 characters'),
             'community_value.max'           => translate('Max 255 characters'),
         ];

         $rules = $this->rules;
         $messages = $this->messages;
         $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
             flash(translate('Something went wrong'))->error();
             return Redirect::back()->withErrors($validator);
         }

         $spiritual_backgrounds = SpiritualBackground::where('user_id', $id)->first();
         if(empty($spiritual_backgrounds)){
             $spiritual_backgrounds          = new SpiritualBackground;
             $spiritual_backgrounds->user_id = $id;
         }

         $spiritual_backgrounds->religion_id        = $request->member_religion_id;
         $spiritual_backgrounds->caste_id           = $request->member_caste_id;
         $spiritual_backgrounds->sub_caste_id       = $request->member_sub_caste_id;
         $spiritual_backgrounds->ethnicity	       = $request->ethnicity;
         $spiritual_backgrounds->personal_value	   = $request->personal_value;
         $spiritual_backgrounds->family_value_id	   = $request->family_value_id;
         $spiritual_backgrounds->community_value	   = $request->community_value;

         if($spiritual_backgrounds->save()){
             flash(translate('Spiritual Background info has been updated successfully'))->success();
             return back();
         }
         else {
             flash(translate('Sorry! Something went wrong.'))->error();
             return back();
         }

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
}
