<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemberLanguage;
use Redirect;
use Validator;

class MemberLanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_member_languages'])->only('index');
        $this->middleware(['permission:edit_member_language'])->only('edit');
        $this->middleware(['permission:delete_member_language'])->only('destroy');

        $this->language_rules = [
            'name' => ['required','max:255'],
        ];

        $this->language_messages = [
            'name.required'             => translate('Language Name is required'),
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
         $languages    = MemberLanguage::latest();

         if ($request->has('search')){
             $sort_search  = $request->search;
             $languages    = $languages->where('name', 'like', '%'.$sort_search.'%');
         }
         $languages = $languages->paginate(10);
         return view('admin.member_profile_attributes.member_languages.index', compact('languages','sort_search'));
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
        $rules = $this->language_rules;
        $messages = $this->language_messages;
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $language       = new MemberLanguage;
        $language->name = $request->name;
        if($language->save()){
            flash(translate('New language has been added successfully'))->success();
            return redirect()->route('member-languages.index');
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
        $language = MemberLanguage::findOrFail(decrypt($id));
        return view('admin.member_profile_attributes.member_languages.edit',compact('language'));
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
        $rules = $this->language_rules;
        $messages = $this->language_messages;
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            flash(translate('Sorry! Something went wrong'))->error();
            return Redirect::back()->withErrors($validator);
        }

        $language       = MemberLanguage::findOrFail($id);
        $language->name = $request->name;
        if($language->save()){
            flash(translate('Language info has been updated successfully'))->success();
            return back();
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
        if (MemberLanguage::destroy($id)) {
            flash(translate('Language info has been deleted successfully'))->success();
            return redirect()->route('member-languages.index');
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }
}
