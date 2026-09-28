<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;

class PaymentTypesController extends Controller
{
    public function getList(Request $request)
    {
        $payment_types = array();

        if (get_setting('paypal_payment_activation') == 1) {
            $payment_type = array();
            $payment_type['payment_type'] = 'paypal_payment';
            $payment_type['payment_type_key'] = 'paypal';
            $payment_type['image'] = static_asset('assets/img/payment_method/paypal.png');
            $payment_type['name'] = "Paypal";
            $payment_type['title'] = "Checkout with Paypal";
            $payment_type['offline_payment_id'] = 0;
            $payment_type['details'] = "";
            $payment_types[] = $payment_type;
        }

        if (get_setting('stripe_payment_activation') == 1) {
            $payment_type = array();
            $payment_type['payment_type'] = 'stripe_payment';
            $payment_type['payment_type_key'] = 'stripe';
            $payment_type['image'] = static_asset('assets/img/payment_method/stripe.png');
            $payment_type['name'] = "Stripe";
            $payment_type['title'] = "Checkout with Stripe";
            $payment_type['offline_payment_id'] = 0;
            $payment_type['details'] = "";


            $payment_types[] = $payment_type;
        }
        // if (get_setting('instamojo_payment_activation') == 1) {
        //     $payment_type = array();
        //     $payment_type['payment_type'] = 'instamojo_payment_';
        //     $payment_type['payment_type_key'] = 'instamojo';
        //     $payment_type['image'] = static_asset('assets/img/payment_method/instamojo.png');
        //     $payment_type['name'] = "Instamojo";
        //     $payment_type['title'] = "Checkout with Instamojo";
        //     $payment_type['offline_payment_id'] = 0;
        //     $payment_type['details'] = "";


        //     $payment_types[] = $payment_type;
        // }

        // if (get_setting('razorpay_payment_activation') == 1) {
        //     $payment_type = array();
        //     $payment_type['payment_type'] = 'razorpay';
        //     $payment_type['payment_type_key'] = 'razorpay';
        //     $payment_type['image'] = static_asset('assets/img/payment_method/rozarpay.png');
        //     $payment_type['name'] = "Razorpay";
        //     $payment_type['title'] = "Checkout with Razorpay";
        //     $payment_type['offline_payment_id'] = 0;
        //     $payment_type['details'] = "";


        //     $payment_types[] = $payment_type;
        // }

        // if (get_setting('paystack_payment_activation') == 1) {
        //     $payment_type = array();
        //     $payment_type['payment_type'] = 'paystack';
        //     $payment_type['payment_type_key'] = 'paystack';
        //     $payment_type['image'] = static_asset('assets/img/payment_method/paystack.png');
        //     $payment_type['name'] = "Paystack";
        //     $payment_type['title'] = "Checkout with Paystack";
        //     $payment_type['offline_payment_id'] = 0;
        //     $payment_type['details'] = "";


        //     $payment_types[] = $payment_type;
        // }
        //African Payment Gateways
        // if (get_setting('paytm_payment_activation') == 1) {
        //     $payment_type = array();
        //     $payment_type['payment_type'] = 'paytm';
        //     $payment_type['payment_type_key'] = 'paytm';
        //     $payment_type['image'] = static_asset('assets/img/payment_method/paytm.jpg');
        //     $payment_type['name'] = "Paytm";
        //     $payment_type['title'] = "Checkout with Paytm";
        //     $payment_type['offline_payment_id'] = 0;
        //     $payment_type['details'] = "";


        //     $payment_types[] = $payment_type;
        // }

        if ($request->payment_type !== 'wallet_recharge') {
            // you cannot recharge wallet by wallet or cash payment
            if (get_setting('wallet_system') == 1) {
                $payment_type = array();
                $payment_type['payment_type'] = 'wallet_system';
                $payment_type['payment_type_key'] = 'wallet';
                $payment_type['image'] = static_asset('assets/img/payment_method/wallet.png');
                $payment_type['name'] = "Wallet";
                $payment_type['title'] = "Wallet Payment";
                $payment_type['offline_payment_id'] = 0;
                $payment_type['details'] = "";

                $payment_types[] = $payment_type;
            }
        }

        return $this->response_data($payment_types);
    }
}
