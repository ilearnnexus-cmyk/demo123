<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Validator;
use Redirect;
class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
       
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
         $address_type = $request->address_type;
         if($address_type == 'present'){
             $this->rules = [
                 'present_country_id'   => [ 'required'],
                 'present_state_id'     => [ 'required'],
                 'present_city_id'      => [ 'required'],
                 'present_postal_code'  => [ 'required'],
             ];
             $this->messages = [
                 'present_country_id.required'  => translate('Country is required'),
                 'present_state_id.required'    => translate('State Name is required'),
                 'present_city_id.required'     => translate('City Name is required'),
                 'present_postal_code.required' => translate('Postal Code is required'),
             ];
         }
         elseif($address_type == 'permanent'){
             $this->rules = [
                 'permanent_country_id'   => [ 'required'],
                 'permanent_state_id'     => [ 'required'],
                 'permanent_city_id'      => [ 'required'],
                 'permanent_postal_code'  => [ 'required'],
             ];
             $this->messages = [
                 'permanent_country_id.required'  => translate('Country is required'),
                 'permanent_state_id.required'    => translate('State Name is required'),
                 'permanent_city_id.required'     => translate('City Name is required'),
                 'permanent_postal_code.required' => translate('Postal Code is required'),
             ];
         }

         $rules = $this->rules;
         $messages = $this->messages;
         $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
             flash(translate('Something went wrong'))->error();
             return Redirect::back()->withErrors($validator);
         }

         $address = Address::where('user_id', $id)->where('type',$request->address_type)->first();
         if(empty($address)){
             $address = new Address;
             $address->user_id = $id;
         }
         if($address_type == 'present'){
             $address->country_id   = $request->present_country_id;
             $address->state_id     = $request->present_state_id;
             $address->city_id      = $request->present_city_id;
             $address->postal_code  = $request->present_postal_code;
         }
         elseif($address_type == 'permanent'){
             $address->country_id   = $request->permanent_country_id;
             $address->state_id     = $request->permanent_state_id;
             $address->city_id      = $request->permanent_city_id;
             $address->postal_code  = $request->permanent_postal_code;
         }
         $address->type             = $address_type;


         if($address->save()){
             flash(translate('Address info has been updated successfully'))->success();
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
