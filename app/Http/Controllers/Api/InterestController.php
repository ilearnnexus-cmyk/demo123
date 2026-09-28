<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\ChatThread;
use App\Utility\SmsUtility;
use Illuminate\Http\Request;
use App\Utility\EmailUtility;
use App\Models\ExpressInterest;
use App\Services\InterestService;
use App\Http\Requests\InterestRequest;
use App\Http\Controllers\Api\Controller;
use App\Http\Resources\MyInterestResource;
use App\Notifications\DbStoreNotification;
use Illuminate\Support\Facades\Notification;
use Kutia\Larafirebase\Facades\Larafirebase;
use App\Http\Resources\ExpressInterestResource;

class InterestController extends Controller
{
    public function my_interests()
    {
        $interests = ExpressInterest::orderBy('id', 'desc')
            ->where('interested_by', auth()->user()->id)
            ->join('users', 'express_interests.user_id', '=', 'users.id')
            ->select('express_interests.id')
            ->distinct()
            ->paginate(10);

        return MyInterestResource::collection($interests)->additional([
            'result' => true
        ]);
    }

    public function express_interest(Request $request)
    {
        if (User::find($request->user_id)) {
            if (!ExpressInterest::where(['user_id' => $request->user_id, 'interested_by' => auth()->user()->id])->first() || ExpressInterest::where(['user_id' => auth()->user()->id, 'interested_by' => $request->user_id])->first()) {
                $interest = new InterestService();
                $new_interest = $interest->store($request->user_id);

                return ($new_interest) ?
                    $this->success_message('Interest Expressed Sucessfully') :
                    $this->failure_message('Something went wrong');
            }
            return $this->failure_message('Alreary Expressed The Interest');
        }
        return $this->failure_message('Invalid Member to Express Interest.');
    }

    public function interest_requests()
    {
        $interests = ExpressInterest::where('user_id', auth()->user()->id)->latest()->paginate(10);
        return ExpressInterestResource::collection($interests)->additional([
            'result' => true
        ]);
    }

    public function accept_interest(Request $request)
    {
        $interest = new InterestService();
        $accept_interest = $interest->accept($request->interest_id);

        return ($accept_interest) ?
            $this->success_message('Interest has been accepted successfully.') :
            $this->failure_message('Something went wrong');
    }

    public function reject_interest(Request $request)
    {
        $interest = new InterestService();
        $reject_interest = $interest->reject($request->interest_id);

        return ($reject_interest) ?
            $this->success_message('Interest has been rejected successfully.') :
            $this->failure_message('Something went wrong');
    }
}
