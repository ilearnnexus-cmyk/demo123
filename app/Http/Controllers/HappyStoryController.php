<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HappyStory;
use Redirect;
use Validator;
use Auth;

class HappyStoryController extends Controller
{

      public function __construct()
      {
          $this->middleware(['permission:show_happy_stories'])->only('index');
          $this->middleware(['permission:edit_happy_story'])->only('edit');
          $this->middleware(['permission:view_details_happy_story'])->only('show');

          $this->rules = [
              'title'         => ['required','max:255'],
              'details'       => ['required'],
              'partner_name'  => ['required','max:255'],
              'photos'        => ['required'],
          ];

          $this->messages = [
              'title.required'              => translate('Story Title is required'),
              'title.max'                   => translate('Max 255 characters'),
              'details.required'            => translate('Story Details is required'),
              'partner_name.required'       => translate('Partner Name is required'),
              'partner_name.max'            => translate('Max 100 characters'),

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
         $happy_stories  = HappyStory::latest();

         if ($request->has('search')){
             $sort_search     = $request->search;
             // $happy_stories   = $happy_stories->where('name', 'like', '%'.$sort_search.'%');
         }
         $happy_stories = $happy_stories->paginate(18);
         return view('admin.happy_stories.index', compact('happy_stories','sort_search'));
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('frontend.member.happy_story.index');
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

      $story                  = new HappyStory;
      $story->user_id         = Auth::user()->id;
      $story->title           = $request->title;
      $story->details         = $request->details;
      $story->partner_name    = $request->partner_name;
      $story->photos          = $request->photos;
      $story->video_provider  = $request->video_provider;
      $story->video_link      = $request->video_link;
      if($story->save()){
          flash(translate('Story uploaded successfully'))->success();
          return redirect()->route('happy-story.create');
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
      $happy_story   = HappyStory::findOrFail(decrypt($id));
      return view('admin.happy_stories.view', compact('happy_story'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $happy_story   = HappyStory::findOrFail(decrypt($id));
      return view('admin.happy_stories.edit', compact('happy_story'));
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

        $story                  = HappyStory::findOrFail($id);
        $story->title           = $request->title;
        $story->details         = $request->details;
        $story->partner_name    = $request->partner_name;
        $story->photos          = $request->photos;
        $story->video_provider  = $request->video_provider;
        $story->video_link      = $request->video_link;
        if($story->save()){
            flash(translate('Story updated successfully'))->success();
            return back();
        } else {
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    public function approval_status(Request $request){
        $happy_story = HappyStory::findOrFail($request->id);
        $happy_story->approved = $request->status;
        if($happy_story->save()){
            return 1;
        }
        return 0;
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
