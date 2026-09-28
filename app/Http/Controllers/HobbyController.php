<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hobby;

class HobbyController extends Controller
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
         $hobbies = Hobby::where('user_id', $id)->first();
         if(empty($hobbies)){
             $hobbies = new Hobby;
             $hobbies->user_id = $id;
         }

         $hobbies->hobbies              = $request->hobbies;
         $hobbies->interests            = $request->interests;
         $hobbies->music                = $request->music;
         $hobbies->books                = $request->books;
         $hobbies->movies               = $request->movies;
         $hobbies->tv_shows             = $request->tv_shows;
         $hobbies->sports               = $request->sports;
         $hobbies->fitness_activities   = $request->fitness_activities;
         $hobbies->cuisines             = $request->cuisines;
         $hobbies->dress_styles         = $request->dress_styles;

         if($hobbies->save()){
             flash(translate('Hobby and Interests info has been updated successfully'))->success();
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
