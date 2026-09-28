<?php

namespace App\Http\Controllers\Api;

use App\Models\HappyStory;
use Illuminate\Http\Request;
use App\Services\HappyStoryService;
use Illuminate\Auth\Events\Validated;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\HappyStoryRequest;
use App\Http\Resources\HappyStoryResource;

class HappyStoryController extends Controller
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

    public function happy_story_check(){
      $happy_story =  HappyStory::where('user_id', auth()->id())->first();
      if(!$happy_story){
        return $this->failure_message('Happy Story Does Not Found!!');
      }else{
        return $this->response_data(new HappyStoryResource($happy_story));
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(HappyStoryRequest $request)
    {
        $photo = null;
        if ($request->hasFile('photos')) {
            $photos = upload_api_file($request->file('photos'));
        }
        if(HappyStory::where('user_id', auth()->id())->first()){
            return $this->failure_message('Happy Story Already Exist');
        }
        $story = new HappyStoryService();
        $happy_story = $story->store($request->except(['_token']), $photos);

        if ($happy_story) {
            return $this->success_message('Story uploaded successfully');
        }
        return $this->failure_message('Something went wrong');
    }

    public function happy_stories()
    {
        $happy_stories = HappyStory::where('approved', 1)->latest()->paginate(12);
        return HappyStoryResource::collection($happy_stories)->additional([
                'result' => true
            ]);
    }

    public function story_details(Request $request)
    {
        $happy_story = HappyStory::where('id', $request->story_id)->where('approved', 1)->first();
        return (new HappyStoryResource($happy_story))->additional([
                'result' => true
            ]);
    }

    public function happy_story()
    {
        $happy_story = HappyStory::where('user_id', auth()->user()->id)->first();
        if ($happy_story) {
            return (new HappyStoryResource($happy_story))->additional([
                'result' => true
            ]);
        }
        return $this->failure_message('Invalid Data!');
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
