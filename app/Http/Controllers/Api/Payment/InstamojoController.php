<?php

namespace App\Http\Controllers\Api\payment;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\APi\WalletController;
use App\Http\Controllers\Api\PackageController;

class InstamojoController extends Controller
{
    public function pay(Request $request)
    {
        if ($request->payment_type) {
            if (get_setting('instamojo_sandbox') == 1) {
                // testing_url
                $endPoint = 'https://test.instamojo.com/api/1.1/';
            } else {
                // live_url
                $endPoint = 'https://www.instamojo.com/api/1.1/';
            }

            $api = new \Instamojo\Instamojo(
                env('INSTAMOJO_API_KEY'),
                env('INSTAMOJO_AUTH_TOKEN'),
                $endPoint
            );
            
            $data = array();
            $data['payment_type']   = $request->payment_type;
            $data['amount']         = $request->amount;
            $data['payment_method'] = $request->payment_method;
            $data['user_id']        = $request->user_id;
            $data['package_id']     = 0;
            if(isset($request->package_id)) {
                $data['package_id'] = $request->package_id;
            }

            //    if (Session::get('payment_type') == 'package_payment') {
            try {
                $response = $api->paymentRequestCreate(array(
                    "purpose"      => ucfirst(str_replace('_', ' ', $request->payment_type)),
                    "amount"       => round($request->amount),
                    "send_email"   => true,
                    "email"        => auth()->user()->email,
                    "phone"        => auth()->user()->phone,
                    "redirect_url" => url('instamojo/payment/pay-success')
                ));

                return response()->json(['result' => true, 'url' => $response['longurl'], 'message' => "Found redirect url"]);
            } catch (Exception $e) {
                return response()->json(['result' => false, 'url' => '', 'message' => "Could not find redirect url"]);
            }
            //    }
        }
    }


    // success response method.
    public function success(Request $request)
    {
        try {
            if (get_setting('instamojo_sandbox') == 1) {
                $endPoint = 'https://test.instamojo.com/api/1.1/';
            } else {
                $endPoint = 'https://www.instamojo.com/api/1.1/';
            }

            $api = new \Instamojo\Instamojo(
                env('INSTAMOJO_API_KEY'),
                env('INSTAMOJO_AUTH_TOKEN'),
                $endPoint
            );

            $response = $api->paymentRequestStatus(request('payment_request_id'));

            if (!isset($response['payments'][0]['status'])) {
                return $this->failure_message('Payment Failed');
            } else if ($response['payments'][0]['status'] != 'Credit') {
                return $this->failure_message('Payment Failed');
            }
        } catch (\Exception $e) {
            return $this->failure_message('Payment Failed');
        }

        $payment = json_encode($response);
        $payment_data = ["package_id" => $request->package_id, "payment_method" => $request->payment_method, "amount" => $request->amount];

        if ($request->payment_type) {
            if ($request->payment_type == 'package_payment') {
                $packagePaymentController = new PackageController;
                return $packagePaymentController->package_payment_done($request->user_id,$payment_data, $payment);
            } elseif ($request->payment_type == 'wallet_payment') {
                $walletController = new WalletController;
                return $walletController->wallet_payment_done($request->user_id,$payment_data, json_encode($payment));
            }
        }
    }
}
