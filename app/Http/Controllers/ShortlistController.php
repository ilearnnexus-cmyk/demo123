<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shortlist;
use Auth;

class ShortlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shortlists = Shortlist::where('shortlisted_by', Auth::user()->id)
                              ->WhereNotIn("user_id", function ($query){
                                    $query->select('user_id')
                                        ->from('ignored_users')
                                        ->where('ignored_by',Auth::user()->id)->orWhere('user_id', Auth::user()->id);})
                              ->WhereNotIn("user_id", function ($query){
                                    $query->select('ignored_by')
                                        ->from('ignored_users')
                                        ->where('ignored_by',Auth::user()->id)->orWhere('user_id', Auth::user()->id);})
                              ->latest()->paginate(10);
        return view('frontend.member.my_shortlists', compact('shortlists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $shortlist                 = new Shortlist;
        $shortlist->user_id        = $request->id;
        $shortlist->shortlisted_by = Auth::user()->id;
        if($shortlist->save()){
            return 1;
        }
        else {
            return 0;
        }
    }
    public function remove(Request $request)
    {
        $shortlist = Shortlist::where('user_id', $request->id)->where('shortlisted_by', Auth::user()->id)->first()->id;
        if(Shortlist::destroy($shortlist)){
            return 1;
        }
        else {
            return 0;
        }
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
    public function destroy()
    {
    }
}
