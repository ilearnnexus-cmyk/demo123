<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function pay()
    {
        return view('frontend.payment_gateway.stripe');
    }

    public function create_checkout_session(Request $request) {
        $amount = 0;
        if($request->session()->has('payment_type')){
            // if ($request->session()->get('payment_type') == 'package_payment') {
                $amount = round(Session::get('payment_data')['amount'] * 100);
            // }
        }

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
            'success_url' => route('stripe.success'),
            'cancel_url' => route('stripe.cancel'),
        ]);

        return response()->json(['id' => $session->id, 'status' => 200]);
    }

    public function success() {
        try{
            $payment = ["status" => "Success"];
            if(Session::has('payment_type')){
              if (Session::get('payment_type') == 'package_payment') {
                  $packagePaymentController = new PackagePaymentController;
                  return $packagePaymentController->package_payment_done(session()->get('payment_data'), json_encode($payment));
              }
              elseif (Session::get('payment_type') == 'wallet_payment') {
                $walletController = new WalletController;
                return $walletController->wallet_payment_done(session()->get('payment_data'), json_encode($payment));
              }
            }
        }
        catch (\Exception $e) {
            flash(translate('Payment failed'))->error();
    	    return redirect()->route('home');
        }
    }

    public function cancel(Request $request){
        flash(translate('Payment is cancelled'))->error();
        return redirect()->route('home');
    }
}
