<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FamilyValue;
use Redirect;
use Validator;

class FamilyValueController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_family_values'])->only('index');
        $this->middleware(['permission:edit_family_value'])->only('edit');
        $this->middleware(['permission:delete_family_value'])->only('destroy');

        $this->family_value_rules = [
            'name' => ['required','max:255'],
        ];

        $this->family_value_messages = [
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
        $family_values = FamilyValue::latest();

        if ($request->has('search')){
            $sort_search    = $request->search;
            $family_values  = $family_values->where('name', 'like', '%'.$sort_search.'%');
        }
        $family_values = $family_values->paginate(10);
        return view('admin.member_profile_attributes.family_values.index', compact('family_values','sort_search'));
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
        $rules      = $this->family_value_rules;
        $messages   = $this->family_value_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $family_value       = new FamilyValue;
        $family_value->name = $request->name;
        if($family_value->save()){
            flash(translate('New family value has been added successfully'))->success();
            return redirect()->route('family-values.index');
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
        $family_value   = FamilyValue::findOrFail(decrypt($id));
        return view('admin.member_profile_attributes.family_values.edit', compact('family_value'));
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
        $rules      = $this->family_value_rules;
        $messages   = $this->family_value_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $family_value       = FamilyValue::findOrFail($id);
        $family_value->name = $request->name;
        if($family_value->save()){
            flash(translate('Family value has been updated successfully'))->success();
            return redirect()->route('family-values.index');
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
        if (FamilyValue::destroy($id)) {
            flash(translate('Family Value info has been deleted successfully'))->success();
            return redirect()->route('family-values.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }
}
