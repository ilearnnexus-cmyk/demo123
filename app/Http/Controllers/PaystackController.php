<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Session;
use Redirect;
use Paystack;

class PaystackController extends Controller
{
    public function redirectToGateway(Request $request)
    {
        $user = Auth::user();
        $request->email = $user->email;
        $request->amount = round(Session::get('payment_data')['amount'] * 100);
        $request->currency = env('PAYSTACK_CURRENCY_CODE', 'NGN');
        $request->reference = Paystack::genTranxRef();
        return Paystack::getAuthorizationUrl()->redirectNow();
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
        if(Session::has('payment_type')){
            $payment = Paystack::getPaymentData();
            $payment_detalis = json_encode($payment);
            if (!empty($payment['data']) && $payment['data']['status'] == 'success') {
                if (Session::get('payment_type') == 'package_payment') {
                    $packagePaymentController = new PackagePaymentController;
                    return $packagePaymentController->package_payment_done(Session::get('payment_data'), $payment_detalis);
                }
                elseif (Session::get('payment_type') == 'wallet_payment') {
                  $walletController = new WalletController;
                  return $walletController->wallet_payment_done(Session::get('payment_data'), $payment_detalis);
                }
            }

            Session::forget('payment_data');
            Session::forget('payment_type');
            flash(translate('Payment cancelled'))->error();
            return redirect()->route('home');

        }
    }
}
