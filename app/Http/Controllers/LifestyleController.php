<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lifestyle;
use Validator;
use Redirect;

class LifestyleController extends Controller
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
             'diet'         => [ 'max:255'],
             'drink'        => [ 'max:255'],
             'smoke'        => [ 'max:255'],
             'living_with'  => [ 'max:255'],
         ];
         $this->messages = [
             'diet.max'             => translate('Max 255 characters'),
             'drink.max'            => translate('Max 255 characters'),
             'smoke.max'            => translate('Max 255 characters'),
             'living_with.max'      => translate('Max 255 characters'),
         ];

         $rules = $this->rules;
         $messages = $this->messages;
         $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
             flash(translate('Something went wrong'))->error();
             return Redirect::back()->withErrors($validator);
         }

         $lifestyle = Lifestyle::where('user_id', $id)->first();
         if(empty($lifestyle)){
             $lifestyle             = new Lifestyle;
             $lifestyle->user_id    = $id;
         }

         $lifestyle->diet          = $request->diet;
         $lifestyle->drink         = $request->drink;
         $lifestyle->smoke         = $request->smoke;
         $lifestyle->living_with   = $request->living_with;

         if($lifestyle->save()){
             flash(translate('Lifestyle info has been updated successfully'))->success();
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
