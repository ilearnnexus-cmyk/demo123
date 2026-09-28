<?php

namespace App\Http\Controllers\Api\Payment;

use Razorpay\Api\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\PackageController;

class RazorpayController extends Controller
{
    public function payWithRazorpay(Request $request)
    {
        $payment_type   = $request->payment_type;
        $amount         = $request->amount;
        $payment_method = $request->payment_method;
        $package_id     = 0;
        if(isset($request->package_id)){
            $package_id = $request->package_id;
        }

        return view('frontend.payment_gateway.razorpay_app', compact('amount', 'package_id', 'payment_method', 'payment_type'));
    }

    public function payment(Request $request)
    {
        //Input items of form
        $input = $request->all();
        //get API Configuration
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if (count($input)  && !empty($input['razorpay_payment_id'])) {
            $payment_detalis = null;
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                $payment_detalis = json_encode(array('id' => $response['id'], 'method' => $response['method'], 'amount' => $response['amount'], 'currency' => $response['currency']));
            } catch (\Exception $e) {
                return  $e->getMessage();
            }

            $payment_data = ["package_id" => $request->package_id, "payment_method" => $request->payment_method, "amount" => $request->amount];
            // Do something here for store payment details in database...
            if ($request->payment_type) {
                if ($request->payment_type == 'package_payment') {
                    $packagePaymentController = new PackageController;
                    return $packagePaymentController->package_payment_done($request->user_id,$payment_data, $payment_detalis);
                } elseif ($request->payment_type == 'wallet_payment') {
                    $walletController = new WalletController;
                    return $walletController->wallet_payment_done($request->user_id,$payment_data, json_encode($payment_detalis));
                }
            }
        }
    }
    public function success(Request $request)
    {
        try {
            $payment_data = ["package_id" => $request->package_id, "payment_method" => $request->payment_method, "amount" => $request->amount];
            $response = $request->payment_details;
            if ($request->payment_type) {
                if ($request->payment_type == 'package_payment') {
                    $packagePaymentController = new PackageController;
                    return $packagePaymentController->package_payment_done($request->user_id,$payment_data, json_encode($response));
                } elseif ($request->payment_type == 'wallet_payment') {
                    $walletController = new WalletController;
                    return $walletController->wallet_payment_done($request->user_id,$payment_data, json_encode($response));
                }
            }

        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()]);
        }
    }
}
