<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;
use Validator;
use Redirect;

class CareerController extends Controller
{
    public function __construct()
    {
        $this->rules = [
            'designation'  => [ 'required','max:255'],
            'company'      => [ 'required','max:255'],
            'career_start' => [ 'required','numeric'],
            'career_end'   => [ 'numeric', 'nullable'],
        ];
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
     public function create(Request $request)
     {
         $member_id = $request->id;
         return view('frontend.member.profile.career.create', compact('member_id'));
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(Request $request)
     {
         $rules = $this->rules;
         $validator = Validator::make($request->all(), $rules);

         if ($validator->fails()) {
             flash(translate('Something went wrong'))->error();
             return Redirect::back();
         }

         $career              = new Career;
         $career->user_id     = $request->user_id;
         $career->designation = $request->designation;
         $career->company     = $request->company;
         $career->start       = $request->career_start;
         $career->end         = $request->career_end;

         if($career->save()){
             flash(translate('Career Info has been added successfully'))->success();
             return back();
         }
         else {
             flash(translate('Sorry! Something went wrong.'))->error();
             return back();
         }
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

      public function edit(Request $request)
      {
          $career = Career::findOrFail($request->id);
          return view('frontend.member.profile.career.edit', compact('career'));
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
         $rules = $this->rules;
         $validator = Validator::make($request->all(), $rules);

         if ($validator->fails()) {
             flash(translate('Something went wrong'))->error();
             return Redirect::back();
         }

         $career              = Career::findOrFail($id);
         $career->designation = $request->designation;
         $career->company     = $request->company;
         $career->start       = $request->career_start;
         $career->end         = $request->career_end;

         if($career->save()){
             flash(translate('Career Info has been updated successfully'))->success();
             return back();
         }
         else {
             flash(translate('Sorry! Something went wrong.'))->error();
             return back();
         }
     }

     public function update_career_present_status(Request $request)
     {
         $career = Career::findOrFail($request->id);
         $career->present = $request->status;
         if ($career->save()) {
             $msg = $career->present == 1 ? translate('Enabled') : translate('Disabled');
             flash(translate($msg))->success();
             return 1;
         }
         return 0;
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         if(Career::destroy($id))
         {
             flash(translate('Career info has been deleted successfully'))->success();
             return back();
         }
         else {
             flash(translate('Sorry! Something went wrong.'))->error();
             return back();
         }
     }
}
