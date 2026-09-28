<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnBehalf;
use Redirect;
use Validator;

class OnBehalfController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:show_on_behalves'])->only('index');
        $this->middleware(['permission:edit_on_behalf'])->only('edit');
        $this->middleware(['permission:delete_on_behalf'])->only('destroy');
        
        $this->on_behalf_rules = [
            'name' => ['required','max:255'],
        ];

        $this->on_behalf_messages = [
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
        $sort_search  = null;
        $on_behalves  = OnBehalf::latest();

        if ($request->has('search')){
            $sort_search  = $request->search;
            $on_behalves  = $on_behalves->where('name', 'like', '%'.$sort_search.'%');
        }
        $on_behalves = $on_behalves->paginate(10);
        return view('admin.member_profile_attributes.on_behalfs.index', compact('on_behalves','sort_search'));
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
        $rules      = $this->on_behalf_rules;
        $messages   = $this->on_behalf_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $on_behalf       = new OnBehalf;
        $on_behalf->name = $request->name;
        if($on_behalf->save()){
            flash(translate('New on behalf has been added successfully'))->success();
            return redirect()->route('on-behalf.index');
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
        $on_behalf   = OnBehalf::findOrFail(decrypt($id));
        return view('admin.member_profile_attributes.on_behalfs.edit', compact('on_behalf'));
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
        $rules      = $this->on_behalf_rules;
        $messages   = $this->on_behalf_messages;
        $validator  = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $on_behalf       = OnBehalf::findOrFail($id);
        $on_behalf->name = $request->name;
        if($on_behalf->save()){
            flash(translate('On behalf info has been updated successfully'))->success();
            return redirect()->route('on-behalf.index');
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
        if(OnBehalf::destroy($id)){
            flash(translate('On behalf info has been deleted successfully'))->success();
            return redirect()->route('on-behalf.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }
}
