<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Recidency;
use Validator;
use Redirect;

class RecidencyController extends Controller
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
            'immigration_status' => [ 'max:255'],
         ];
         $this->messages = [
            'immigration_status.max'  => translate('Max 255 characters'),
         ];

         $rules = $this->rules;
         $messages = $this->messages;
         $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
            flash(translate('Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
         }

         $recidencies = Recidency::where('user_id', $id)->first();
         if(empty($recidencies)){
            $recidencies = new Recidency;
            $recidencies->user_id = $id;
         }

         $recidencies->birth_country_id         = $request->birth_country_id;
         $recidencies->recidency_country_id     = $request->recidency_country_id;
         $recidencies->growup_country_id        = $request->growup_country_id;
         $recidencies->immigration_status       = $request->immigration_status;

         if($recidencies->save()){
            flash(translate('Residency Info has been updated successfully'))->success();
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
