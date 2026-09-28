<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use View;
use Session;
use Redirect;
use App\Http\Controllers\PackagePaymentController;
use Auth;

class InstamojoController extends Controller
{
   public function pay($request){
       if(Session::has('payment_type')){

           if(get_setting('instamojo_sandbox') == 1){
               // testing_url
               $endPoint = 'https://test.instamojo.com/api/1.1/';
           }
           else{
               // live_url
               $endPoint = 'https://www.instamojo.com/api/1.1/';
           }

           $api = new \Instamojo\Instamojo(
                env('INSTAMOJO_API_KEY'),
                env('INSTAMOJO_AUTH_TOKEN'),
                $endPoint
              );

        //    if (Session::get('payment_type') == 'package_payment') {
                  try {
                      $response = $api->paymentRequestCreate(array(
                          "purpose" => ucfirst(str_replace('_', ' ', Session::get('payment_type'))),
                          "amount" => round(Session::get('payment_data')['amount']),
                          "send_email" => true,
                          "email" => Auth::user()->email,
                          "phone" => Auth::user()->phone,
                          "redirect_url" => url('instamojo/payment/pay-success')
                          ));

                          return redirect($response['longurl']);

                  }catch (Exception $e) {
                      return back();
                  }
        //    }
       }
 }

// success response method.
 public function success(Request $request){
     try {
         if(get_setting('instamojo_sandbox') == 1){
             $endPoint = 'https://test.instamojo.com/api/1.1/';
         }
         else{
             $endPoint = 'https://www.instamojo.com/api/1.1/';
         }

         $api = new \Instamojo\Instamojo(
             env('INSTAMOJO_API_KEY'),
             env('INSTAMOJO_AUTH_TOKEN'),
             $endPoint
         );

        $response = $api->paymentRequestStatus(request('payment_request_id'));

        if(!isset($response['payments'][0]['status']) ) {
            flash(translate('Payment Failed'))->error();
            return redirect()->route('home');
        } else if($response['payments'][0]['status'] != 'Credit') {
            flash(translate('Payment Failed'))->error();
            return redirect()->route('home');
        }
      }catch (\Exception $e) {
          flash(translate('Payment Failed'))->error();
          return redirect()->route('home');
     }

    $payment = json_encode($response);

    if(Session::has('payment_type')){
        if (Session::get('payment_type') == 'package_payment') {
            $packagePaymentController = new PackagePaymentController;
            return $packagePaymentController->package_payment_done($request->session()->get('payment_data'), $payment);
        }
        elseif ($request->session()->get('payment_type') == 'wallet_payment') {
          $walletController = new WalletController;
          return $walletController->wallet_payment_done($request->session()->get('payment_data'), json_encode($payment));
        }
    }
  }

}
