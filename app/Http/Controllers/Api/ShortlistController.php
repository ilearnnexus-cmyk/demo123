<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Shortlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\ShortlistResource;

class ShortlistController extends Controller
{
    public function index()
    {
        $shortlists = Shortlist::where('shortlisted_by', auth()->user()->id)
                              ->WhereNotIn("user_id", function ($query){
                                    $query->select('user_id')
                                        ->from('ignored_users')
                                        ->where('ignored_by', auth()->user()->id)->orWhere('user_id', auth()->user()->id);})
                              ->WhereNotIn("user_id", function ($query){
                                    $query->select('ignored_by')
                                        ->from('ignored_users')
                                        ->where('ignored_by', auth()->user()->id)->orWhere('user_id', auth()->user()->id);})
                              ->latest()->paginate(10);

        return ShortlistResource::collection($shortlists)->additional([
            'result' => true
        ]);
    }

    public function store(Request $request)
    {
        if(User::find($request->user_id)){
            if (!Shortlist::where('user_id',$request->user_id)->where('shortlisted_by',auth()->id())->first()) {
                Shortlist::create($request->only('user_id') + [
                    'shortlisted_by' => auth()->user()->id
                ]);
                return $this->success_message('You Have Shortlisted This Member');
            }
            return $this->failure_message('You Have already added This Member');
        }
        return $this->failure_message('Invalid Member to Shortlist.');
        
    }
    
    public function remove(Request $request)
    {
        $shortlist = Shortlist::where('user_id', $request->user_id)->where('shortlisted_by', auth()->user()->id)->first();
        if($shortlist){
            Shortlist::destroy($shortlist->id);
            return $this->success_message('You Have Removed This Member From Your Shortlist.');
        }        
        return $this->success_message('Invalid Information');
    }
}
