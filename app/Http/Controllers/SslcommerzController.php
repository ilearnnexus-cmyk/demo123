<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\PackagePaymentController;
use App\Models\User;
use App\Utility\SSLCommerz;
use Auth;
use Illuminate\Support\Facades\Session;

session_start();

class SslcommerzController extends Controller
{
    public function pay(Request $request)
    {
        $amount = 0;
        # Here you have to receive all the order data to initate the payment.
        # Lets your oder trnsaction informations are saving in a table called "orders"
        # In orders table order uniq identity is "order_id","order_status" field contain status of the transaction, "grand_total" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.
        if (Session::has('payment_type')) {
            $amount = round(Session::get('payment_data')['amount']);
            // if (Session::get('payment_type') == 'cart_payment') {
                // dd('cart_payment');

                $post_data = array();
                $post_data['total_amount'] = $amount; # You cant not pay less than 10
                $post_data['currency'] = "BDT";
                $post_data['tran_id'] = substr(md5(auth()->id()), 0, 10); // tran_id must be unique

                $post_data['value_a'] = $post_data['tran_id'];
                $post_data['value_b'] = auth()->id();
                $post_data['value_c'] = $request->session()->get('payment_type');
                if($request->session()->get('payment_type')== 'package_payment'){
                    $post_data['value_d'] = $request->package_id;
                }
            // }

            # CUSTOMER INFORMATION
            $user = auth()->user();
            $post_data['cus_name'] = $user->first_name.' '.$user->last_name;
            $post_data['cus_add1'] = $user->address;
            $post_data['cus_city'] = $user->city;
            $post_data['cus_postcode'] = $user->postal_code;
            $post_data['cus_country'] = $user->country;
            $post_data['cus_phone'] = $user->phone;
            $post_data['cus_email'] = $user->email;
        }

        $server_name = $request->root() . "/";
        // $post_data['success_url'] = $server_name . "sslcommerz/success";
        $post_data['success_url'] = route('sslcommerz.success');
        $post_data['fail_url'] = $server_name . "sslcommerz/fail";
        $post_data['cancel_url'] = $server_name . "sslcommerz/cancel";

        $sslc = new SSLCommerz();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->initiate($post_data, false);
        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }

    public function success(Request $request)
    {
        // echo "Transaction is Successful";
        $sslc = new SSLCommerz();
        #Start to received these value from session. which was saved in index function.
        $tran_id = $request->value_a;
        #End to received these value from session. which was saved in index function.
        $payment = $request->all();
        $payment['payment_method']= $payment['value_c'];
        $payment['package_id']= $payment['value_d'];
        $payment_response = json_encode($payment);
        auth()->login(User::find($payment['value_b']));
        // dd($payment);
        // dd($payment['value_c']);
        // dd($payment['value_d']);

        if (isset($request->value_c)) {

            if ($payment['value_c'] == 'package_payment') {
                $packagePaymentController = new PackagePaymentController;
                return $packagePaymentController->package_payment_done($payment, $payment_response);
            } elseif ($payment['value_c'] == 'wallet_payment') {
                $walletController = new WalletController;
                return $walletController->wallet_payment_done($payment, $payment_response);
            }
        }
    }

    public function fail(Request $request)
    {
        $request->session()->forget('payment_data');
        $request->session()->forget('payment_type');
        flash(translate('Payment Failed'))->error();
        return redirect()->route('home');
    }

    public function cancel(Request $request)
    {
        $request->session()->forget('payment_data');
        $request->session()->forget('payment_type');
        flash(translate('Payment Failed'))->error();
        return redirect()->route('home');
    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {
            $tran_id = $request->input('tran_id');
            #Check order status in order tabel against the transaction id or order id.
            $sslc = new SSLCommerz();
            $validation = $sslc->orderValidate($tran_id, $order->grand_total, 'BDT', $request->all());
            if ($validation == TRUE) {
                /*
                        That means IPN worked. Here you need to update order status
                        in order table as Processing or Complete.
                        Here you can also sent sms or email for successfull transaction to customer
                        */
                echo "Transaction is successfully Complete";
            } else {
                /*
                        That means IPN worked, but Transation validation failed.
                        Here you need to update order status as Failed in order table.
                        */

                echo "validation Fail";
            }
        }
    }
}
