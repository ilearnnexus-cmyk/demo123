<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success_message($msg){
        return response()->json([
            'result' => true,
            'message' => translate($msg)
        ]);
    }

    public function failure_message($msg){
        return response()->json([
            'result' => false,
            'message' => translate($msg)
        ]);
    }
    public function failure_data($data){
        return response()->json([
            'result' => false,
            'data' => $data
        ]);
    }

    public function response_data($data){
        return response()->json([
            'result' => true,
            'data' => $data
        ]);
    }
}
