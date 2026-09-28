<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Religion;
use Redirect;
use Validator;

class ReligionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_religions'])->only('index');
        $this->middleware(['permission:edit_religion'])->only('edit');
        $this->middleware(['permission:delete_religion'])->only('destroy');

        $this->rules = [
            'name' => ['required','max:255'],
        ];

        $this->messages = [
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
        $religions    = Religion::latest();
        if ($request->has('search')){
            $sort_search  = $request->search;
            $religions    = $religions->where('name', 'like', '%'.$sort_search.'%');
        }
        $religions = $religions->paginate(10);
        return view('admin.member_profile_attributes.religions.index', compact('religions','sort_search'));
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

        $religion       = new Religion;
        $religion->name = $request->name;
        if($religion->save()){
            flash(translate('New religion has been added successfully'))->success();
            return redirect()->route('religions.index');
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
        $religion = Religion::findOrFail(decrypt($id));
        return view('admin.member_profile_attributes.religions.edit',compact('religion'));
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

        $religion       = Religion::findOrFail($id);
        $religion->name = $request->name;
        if($religion->save()){
            flash(translate('Religion info has been updated successfully'))->success();
            return redirect()->route('religions.index');
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
        $religion = Religion::findOrFail($id);
        foreach ($religion->castes as $key => $caste) {
            foreach ($caste->sub_castes as $key => $sub_caste) {
                $sub_caste->delete();
            }
            $caste->delete();
        }
        if (Religion::destroy($id)) {
            flash(translate('Religion info has been deleted successfully'))->success();
            return redirect()->route('religions.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }
}
