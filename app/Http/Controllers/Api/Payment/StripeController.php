<?php

namespace App\Http\Controllers\Api\Payment;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\PackageController;

class StripeController extends Controller
{

    public function stripe(Request $request)
    {
        $data['payment_type']   = $request->payment_type;
        $data['amount']         = $request->amount;
        $data['payment_method'] = $request->payment_method;
        $data['user_id']        = $request->user_id;
        $data['package_id']     = 0;
        if (isset($request->package_id)) {
            $data['package_id'] = $request->package_id;
        }
        return view('frontend.payment_gateway.stripe_app',  $data);
    }

    public function create_checkout_session(Request $request)
    {
        $amount = 0;
        if ($request->payment_type) {
            $amount = round($request->amount * 100);
        }

        $data = array();
        $data['payment_type'] = $request->payment_type;
        $data['amount'] = $request->amount;
        $data['payment_method'] = $request->payment_method;
        $data['package_id'] = $request->package_id;
        $data['user_id']       = $request->user_id;


        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code,
                        'product_data' => [
                            'name' => "Payment"
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('api.stripe.success', $data),
            'cancel_url' => route('api.stripe.cancel'),
        ]);

        return response()->json(['id' => $session->id, 'status' => 200]);
    }

    // 
    public function success(Request $request)
    {
        $payment_data = ["package_id" => $request->package_id, "payment_method" => $request->payment_method, "amount" => $request->amount];
        try {
            $payment = ["status" => "Success"];
            if ($request->payment_type) {
                if ($request->payment_type == 'package_payment') {
                    $packagePaymentController = new PackageController;
                    return $packagePaymentController->package_payment_done($request->user_id, $payment_data, json_encode($payment));
                } elseif ($request->payment_type == 'wallet_payment') {
                    $walletController = new WalletController;
                    return $walletController->wallet_payment_done($request->user_id, $payment_data, json_encode($payment));
                }
            }
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => translate("Payment is unsuccessful")]);
        }
    }

    public function cancel(Request $request)
    {
        return response()->json(['result' => false, 'message' => translate("Payment is cancelled")]);
    }
}
