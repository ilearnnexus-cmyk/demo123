<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Religion;
use App\Models\Caste;
use App\Models\SubCaste;
use Redirect;
use Validator;

class SubCasteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_sub_castes'])->only('index');
        $this->middleware(['permission:edit_sub_caste'])->only('edit');
        $this->middleware(['permission:delete_sub_caste'])->only('destroy');

        $this->subcaste_rules = [
            'name'     => [ 'required','max:255'],
            'caste_id' => [ 'required'],
        ];

        $this->subcaste_messages = [
            'name.required'             => translate('Sub Caste name is required'),
            'name.max'                  => translate('Max 255 characters'),
            'caste_id.required'         => translate('Caste is required'),
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
        $sub_castes    = SubCaste::latest();
        $religions     = Religion::all();
        $castes        = Caste::all();

        if ($request->has('search')){
            $sort_search  = $request->search;
            $sub_castes   = $sub_castes->where('name', 'like', '%'.$sort_search.'%');
        }
        $sub_castes = $sub_castes->paginate(10);
        return view('admin.member_profile_attributes.sub_castes.index', compact('religions','castes','sub_castes','sort_search'));

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
        $rules      = $this->subcaste_rules;
        $messages   = $this->subcaste_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $sub_caste              = new SubCaste;
        $sub_caste->name        = $request->name;
        $sub_caste->caste_id    = $request->caste_id;
        if($sub_caste->save())
        {
            flash(translate('New Sub Caste has been added successfully'))->success();
            return redirect()->route('sub-castes.index');
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
    public function edit($id)
    {
        $sub_caste        = SubCaste::findOrFail(decrypt($id));
        $religions        = Religion::all();
        return view('admin.member_profile_attributes.sub_castes.edit', compact('sub_caste','religions'));
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
        $rules      = $this->subcaste_rules;
        $messages   = $this->subcaste_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $sub_caste              = SubCaste::findOrFail($id);
        $sub_caste->name        = $request->name;
        $sub_caste->caste_id    = $request->caste_id;
        if($sub_caste->save())
        {
            flash(translate('Sub Caste info has been updated successfully'))->success();
            return redirect()->route('sub-castes.index');
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
        $sub_caste = SubCaste::findOrFail($id);
        if (SubCaste::destroy($id)) {
            flash(translate('Sub Caste info has been deleted successfully'))->success();
            return redirect()->route('sub-castes.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    // Get sub castes by religion
    public function get_sub_castes_by_religion(Request $request)
    {
        $sub_castes = SubCaste::where('caste_id', $request->caste_id)->get();
        return $sub_castes;
    }

}
