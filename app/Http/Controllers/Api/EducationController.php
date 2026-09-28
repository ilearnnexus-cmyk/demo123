<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\EducationResource;
use App\Models\Education;
use Exception;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $education = Education::where('user_id',auth()->id())->get();
        return EducationResource::collection($education)->additional([
            'result' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'degree'          => ['required', 'max:255'],
            'institution'     => ['required', 'max:255'],
            'education_start' => ['required', 'numeric'],
            'education_end'   => ['numeric', 'nullable'],
        ]);
        $education              = new Education();
        $education->user_id     = auth()->id();
        $education->degree      = $request->degree;
        $education->institution = $request->institution;
        $education->start       = $request->education_start;
        $education->end         = $request->education_end;
        $education->save();
        return $this->success_message('Education Info has been added successfully');
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
        $this->validate($request, [
            'degree'          => ['required', 'max:255'],
            'institution'     => ['required', 'max:255'],
            'education_start' => ['required', 'numeric'],
            'education_end'   => ['numeric', 'nullable'],
        ]);

        $education = Education::where('id', $id)->where('user_id', auth()->id())->first();
        if ($education) {
            $education->degree      = $request->degree;
            $education->institution = $request->institution;
            $education->start       = $request->education_start;
            $education->end         = $request->education_end;
            $education->save();
            return $this->success_message('Education Info has been updated successfully');
        } else {
            return $this->failure_message('You are not authorized or data not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $education = Education::where('id', $id)->where('user_id', auth()->id())->first();
        if ($education) {
            $education->delete();
            return $this->success_message('Education info has been deleted successfully');
        } else {
            return $this->failure_message('You are not authorized');
        }
    }

    public function education_status_update(Request $request)
    {
        $education = Education::where('id', $request->id)->where('user_id', auth()->id())->first();
        if ($education) {
            $education->present = $request->status;
            $education->save();
            return $this->success_message('Education status has been changed.');
        } else {
            return $this->failure_message('You are not authorized or data not found');
        }
    }
}
