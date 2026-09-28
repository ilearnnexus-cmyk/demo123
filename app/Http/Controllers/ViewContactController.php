<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ViewContact;
use App\Models\Member;
use Auth;

class ViewContactController extends Controller
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
        $view_contact_by_user = Auth::user();
        $view_contact_by_member = $view_contact_by_user->member;

        if($view_contact_by_member->remaining_contact_view > 0){

            // Store view contact data
            $view_contact             = new ViewContact;
            $view_contact->user_id    = $request->id;
            $view_contact->viewed_by  = $view_contact_by_user->id;
            if($view_contact->save()){

                // Deduct View Contact by user's remaining contact views
                $view_contact_by_member->remaining_contact_view -= 1;
                $view_contact_by_member->save();
                return true;
            }
            else {
                return false;
            }
        }
        else{
            return false;
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
}
