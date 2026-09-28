<?php

namespace App\Http\Controllers\Api;

use Exception;
use Mpdf\Tag\Tr;
use App\Models\Career;
use Illuminate\Http\Request;
use App\Http\Requests\CareerRequest;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\CareerResource;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $career = Career::where('user_id',auth()->id())->get();
        return  CareerResource::collection($career)->additional([
            'result' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CareerRequest $request)
    {
        Career::create($request->validated() + [
            'user_id' => auth()->id()
        ]);
        return $this->success_message('Career Info has been added successfully');
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
    public function update(CareerRequest $request, $id)
    {
        $career = Career::where('id', $id)->where('user_id', auth()->id())->first();
        if ($career) {
            $career->update($request->validated());
            return $this->success_message('Career Info has been updated successfully');
        }
        return $this->failure_message('You are not authorized');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $career = Career::where('id', $id)->where('user_id', auth()->id())->first();
        if ($career) {
            $career->delete();
            return $this->success_message('Education info has been deleted successfully');
        } else {
            return $this->failure_message('You are not authorized');
        }
    }

    public function career_status_update(Request $request)
    {
        $career = Career::where('id', $request->id)->where('user_id', auth()->id())->first();
        if ($career) {
            $career->present = $request->status;
            $career->save();
            return $this->success_message('Career status is updated');
        }
        return $this->failure_message('You are not authorized');
    }
}
