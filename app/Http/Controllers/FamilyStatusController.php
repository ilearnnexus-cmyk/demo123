<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamilyStatus;
use Redirect;
use Validator;

class FamilyStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_family_values'])->only('index');
        $this->middleware(['permission:edit_family_value'])->only('edit');
        $this->middleware(['permission:delete_family_value'])->only('destroy');

        $this->family_status_rules = [
            'name' => ['required','max:255'],
        ];

        $this->family_status_messages = [
            'name.required'             => translate('Name is required'),
            'name.max'                  => translate('Max 255 characters'),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $sort_search   = null;
       $family_statuses = FamilyStatus::latest();

       if ($request->has('search')){
           $sort_search    = $request->search;
           $family_statuses  = $family_statuses->where('name', 'like', '%'.$sort_search.'%');
       }
       $family_statuses = $family_statuses->paginate(10);
       return view('admin.member_profile_attributes.family_status.index', compact('family_statuses','sort_search'));
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
        $rules      = $this->family_status_rules;
        $messages   = $this->family_status_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $family_status       = new FamilyStatus;
        $family_status->name = $request->name;
        if($family_status->save()){
            flash(translate('New family status has been added successfully'))->success();
            return redirect()->route('family-status.index');
        } else {
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
    public function edit($id)
    {
        $family_status   = FamilyStatus::findOrFail(decrypt($id));
        return view('admin.member_profile_attributes.family_status.edit', compact('family_status'));
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
        $rules      = $this->family_status_rules;
        $messages   = $this->family_status_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $family_status       = FamilyStatus::findOrFail($id);
        $family_status->name = $request->name;
        if($family_status->save()){
            flash(translate('Family status info has been updaed successfully'))->success();
            return redirect()->route('family-status.index');
        } else {
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
        if (FamilyStatus::destroy($id)) {
            flash(translate('Family status info has been deleted successfully'))->success();
            return redirect()->route('family-status.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }
}
