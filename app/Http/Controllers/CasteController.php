<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Religion;
use App\Models\Caste;
use Redirect;
use Validator;

class CasteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_castes'])->only('index');
        $this->middleware(['permission:edit_caste'])->only('edit');
        $this->middleware(['permission:delete_caste'])->only('destroy');

        $this->rules = [
            'name'      => ['required','max:255'],
            'religion'  => ['required'],
        ];

        $this->messages = [
            'name.required'     => translate('Name is required'),
            'name.max'          => translate('Max 255 characters'),
            'religion.required' => translate('Religion is required'),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search  = null;
        $castes       = Caste::latest();
        $religions    = Religion::all();

        if ($request->has('search')){
            $sort_search  = $request->search;
            $castes       = $castes->where('name', 'like', '%'.$sort_search.'%');
        }
        $castes = $castes->paginate(10);
        return view('admin.member_profile_attributes.castes.index', compact('castes','religions','sort_search'));
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
        $rules      = $this->rules;
        $messages   = $this->messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $caste              = new Caste;
        $caste->name        = $request->name;
        $caste->religion_id = $request->religion;
        if($caste->save())
        {
            flash('New caste has been added successfully')->success();
            return redirect()->route('castes.index');
        }
        else {
            flash('Sorry! Something went wrong.')->error();
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
        $caste          = Caste::findOrFail(decrypt($id));
        $religions      = Religion::all();
        return view('admin.member_profile_attributes.castes.edit', compact('caste','religions'));
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
        $rules      = $this->rules;
        $messages   = $this->messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $caste              = Caste::findOrFail($id);
        $caste->name        = $request->name;
        $caste->religion_id = $request->religion;
        if($caste->save())
        {
            flash('Caste info has been updated successfully')->success();
            return redirect()->route('castes.index');
        }
        else {
            flash('Sorry! Something went wrong.')->error();
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
        $caste = Caste::findOrFail($id);
        foreach ($caste->sub_castes as $key => $sub_caste) {
            $sub_caste->delete();
        }
        if (Caste::destroy($id)) {
            flash('Caste info has been deleted successfully')->success();
            return redirect()->route('castes.index');
        } else {
            flash('Sorry! Something went wrong.')->error();
            return back();
        }
    }

    // Get castes by religion
    public function get_caste_by_religion(Request $request)
    {
        $castes = Caste::where('religion_id', $request->religion_id)->get();
        return $castes;
    }
}
