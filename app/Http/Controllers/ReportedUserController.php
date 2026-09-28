<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReportedUser;
use Auth;

class ReportedUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_reported_profile'])->only('reported_members');
        $this->middleware(['permission:delete_profile_report'])->only('destroy');
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
        $report_member             = new ReportedUser;
        $report_member->user_id    = $request->member_id;
        $report_member->reported_by= Auth::user()->id;
        $report_member->reason     = $request->reason;
        if($report_member->save()){
          flash('Reported to this member successfully.')->success();
          return back();
        }
        else {
          flash('Sorry! Something went wrong.')->error();
          return back();
        }
    }

    public function reported_members($id)
    {
      $reports       = ReportedUser::latest();
      if($id != 'all')
      {
        $reports  = $reports->where('user_id',$id);
      }
      $reports       = $reports->paginate(10);
      return view('admin.members.reported_members', compact('reports'));
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
      if(ReportedUser::destroy($id)){
          flash(translate('Report deleted successfully'))->success();
          return redirect()->route('reported_members','all');
      }
      else {
          flash(translate('Sorry! Something went wrong.'))->error();
          return back();
      }
    }
}
