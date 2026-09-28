<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\PackagePaymentController;

use Session;
use Auth;

class AamarpayController extends Controller
{
    public function pay(){
        // dd('ok');
        if (auth()->user()->phone == null) {
            flash(translate('Please add phone number to your profile'))->warning();
            return redirect()->route('profile');
        }
        
        if (auth()->user()->email == null) {
            $email = 'customer@exmaple.com';
        }
        else{
            $email = auth()->user()->email;
        }

        if (get_setting('aamarpay_sandbox') == 1) {
            $url = 'https://sandbox.aamarpay.com/request.php'; // live url https://secure.aamarpay.com/request.php
        }
        else {
            $url = 'https://secure.aamarpay.com/request.php';
        }

        $amount = 0;
        if(session()->has('payment_type')){
            $amount = round(session()->get('payment_data')['amount']);
        }

        $fields = array(
            'store_id' => env('AAMARPAY_STORE_ID'), //store id will be aamarpay,  contact integration@aamarpay.com for test/live id
            'amount' => $amount, //transaction amount
            'payment_type' => 'VISA', //no need to change
            'currency' => 'BDT',  //currenct will be USD/BDT
            'tran_id' => rand(1111111,9999999), //transaction id must be unique from your end
            'cus_name' => auth()->user()->name,  //customer name
            'cus_email' => $email, //customer email address
            'cus_add1' => '',  //customer address
            'cus_add2' => '', //customer address
            'cus_city' => '',  //customer city
            'cus_state' => '',  //state
            'cus_postcode' => '', //postcode or zipcode
            'cus_country' => 'Bangladesh',  //country
            'cus_phone' => auth()->user()->phone, //customer phone number
            'cus_fax' => 'Not¬Applicable',  //fax
            'ship_name' => '', //ship name
            'ship_add1' => '',  //ship address
            'ship_add2' => '',
            'ship_city' => '',
            'ship_state' => '',
            'ship_postcode' => '',
            'ship_country' => 'Bangladesh',
            'desc' => env('APP_NAME').' payment',
            'success_url' => route('aamarpay.success'), //your success route
            'fail_url' => route('aamarpay.fail'), //your fail route
            'cancel_url' => route('home'), //your cancel url
            'opt_a' => session()->get('payment_type'),  //optional paramter
            'opt_b' => session()->get('combined_order_id'),
            'opt_c' => json_encode(session()->get('payment_data')),
            'opt_d' => '',
            'signature_key' => env('AAMARPAY_SIGNATURE_KEY') //signature key will provided aamarpay, contact integration@aamarpay.com for test/live signature key
        );
        // dd($fields);
        $fields_string = http_build_query($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $url_forward = str_replace('"', '', stripslashes(curl_exec($ch)));
        curl_close($ch);

        $this->redirect_to_merchant($url_forward);
    }

    function redirect_to_merchant($url) {
        if (get_setting('aamarpay_sandbox') == 1) {
            $base_url = 'https://sandbox.aamarpay.com/';
        }
        else {
            $base_url = 'https://secure.aamarpay.com/';
        }
        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
          <head><script type="text/javascript">
            function closethisasap() { document.forms["redirectpost"].submit(); }
          </script></head>
          <body onLoad="closethisasap();">
            <form name="redirectpost" method="post" action="<?php echo $base_url.$url; ?>"></form>
          </body>
        </html>
        <?php
        exit;
    }

    public function success(Request $request){
        $payment_type = $request->opt_a;
        if ($request->session()->get('payment_type') == 'package_payment') {
            $packagePaymentController = new PackagePaymentController;
            return $packagePaymentController->package_payment_done($request->session()->get('payment_data'), $payment_type);
        }
        elseif ($request->session()->get('payment_type') == 'wallet_payment') {
          $walletController = new WalletController;
          return $walletController->wallet_payment_done($request->session()->get('payment_data'), $payment_type);
        }
    }

    public function fail(Request $request){
        $request->session()->forget('payment_data');
        $request->session()->forget('payment_type');
        flash(translate('Payment Failed'))->error();
        return redirect()->route('home');
    }
}
