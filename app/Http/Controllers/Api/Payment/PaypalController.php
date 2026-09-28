<?php

namespace App\Http\Controllers\Api\Payment;

use Illuminate\Http\Request;
use PayPalHttp\HttpException;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\WalletController;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use App\Http\Controllers\Api\PackageController;
use App\Models\Package;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaypalController extends Controller
{
    public function pay(Request $request)
    {
        $auth_user = auth()->user();
        $clientId     = env('PAYPAL_CLIENT_ID');
        $clientSecret = env('PAYPAL_CLIENT_SECRET');

        if (get_setting('paypal_sandbox') == 1) {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }
        $client = new PayPalHttpClient($environment);
        $amount = $request->amount;

        $data                   = array();
        $data['payment_type']   = $request->payment_type;
        $data['amount']         = $request->amount;
        $data['payment_method'] = $request->payment_method;
        $data['user_id']        = $request->user_id;
        $data['package_id']     = 0;
        if (isset($request->package_id)) {
            $data['package_id'] = $request->package_id;
            $package = Package::where('id', $request->package_id)->first();
        }

        if ($data['payment_type'] == 'package_payment') {
            if (addon_activation('referral_system') && $auth_user->referred_by != null && $auth_user->referral_comission == 0) {
                $referral_discount_amount = get_setting('referral_user_package_purchase_discount');
                $discount_type = get_setting('referral_user_package_purchase_discount_type');
                if ($discount_type == 'percent') {
                    $amount = $request->amount - ($package->price * $referral_discount_amount) / 100;
                } else {
                    $amount = $request->amount - $referral_discount_amount;
                }
                
            }
        }

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => rand(000000, 999999),
                "amount" => [
                    "value" => $amount,
                    "currency_code" => \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code
                ]
            ]],
            "application_context" => [
                "cancel_url" => route('api.paypal.cancel'),
                "return_url" => route('api.paypal.done', $data)
            ]
        ];

        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($request);
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            return response()->json(['result' => true, 'url' => $response->result->links[1]->href, 'message' => "Found redirect url"]);
        } catch (HttpException $ex) {
            return response()->json(['result' => false, 'url' => '', 'message' => "Could not find redirect url"]);
        }
    }

    public function getDone(Request $request)
    {
        // Creating an environment
        $clientId     = env('PAYPAL_CLIENT_ID');
        $clientSecret = env('PAYPAL_CLIENT_SECRET');

        if (get_setting('paypal_sandbox') == 1) {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }
        $client = new PayPalHttpClient($environment);

        // $response->result->id gives the orderId of the order created above

        $ordersCaptureRequest = new OrdersCaptureRequest($request->token);
        $ordersCaptureRequest->prefer('return=representation');
        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($ordersCaptureRequest);

            $payment_data = ["package_id" => $request->package_id, "payment_method" => $request->payment_method, "amount" => $request->amount];

            if ($request->payment_type) {
                if ($request->payment_type == 'package_payment') {
                    $packagePaymentController = new PackageController;
                    return $packagePaymentController->package_payment_done($request->user_id, $payment_data, json_encode($response));
                } elseif ($request->payment_type == 'wallet_payment') {
                    $walletController = new WalletController;
                    return $walletController->wallet_payment_done($request->user_id, $payment_data, json_encode($response));
                }
            }
        } catch (HttpException $ex) {
            return response()->json(['result' => false, 'message' => translate("Payment failed")]);
        }
    }

    public function getCancel(Request $request)
    {
        return response()->json(['result' => true, 'message' => translate("Payment failed or got cancelled")]);
    }
}
