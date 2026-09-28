<?php

namespace App\Http\Controllers\Api\Payment;

use Paystack;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\PackageController;
use App\Models\User;

class PaystackController extends Controller
{
    public function redirectToGateway(Request $request)
    {
        $user = User::where('id',$request->user_id)->first();
        $request->email          = $user->email;
        $request->amount         = round($request->amount * 100);
        $request->payment_type   = $request->payment_type;
        $request->payment_method = $request->payment_method;
        $request->package_id     = 0;
        $request->user_id        = $request->user_id;
        if(isset($request->package_id)){
            $request->package_id = $user->id;
        }
        $request->currency       = env('PAYSTACK_CURRENCY_CODE', 'NGN');
        $request->reference      = Paystack::genTranxRef();
        
        return Paystack::getAuthorizationUrl()->redirectNow();
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback(Request $request)
    {
        $payment_data = ["package_id" => $request->package_id, "payment_method" => $request->payment_method, "amount" => $request->amount];
        if ($request->payment_type) {
            $payment = Paystack::getPaymentData();
            $payment_detalis = json_encode($payment);
            if (!empty($payment['data']) && $payment['data']['status'] == 'success') {
                if ($request->payment_type == 'package_payment') {
                    $packagePaymentController = new PackageController;
                    return $packagePaymentController->package_payment_done($request->user_id,$payment_data, $payment_detalis);
                } elseif ($request->payment_type == 'wallet_payment') {
                    $walletController = new WalletController;
                    return $walletController->wallet_payment_done($request->user_id,$payment_data, $payment_detalis);
                }
            }
            return response()->json(['result' => false, 'message' => translate("Payment cancelled")]);
        }
    }
}
