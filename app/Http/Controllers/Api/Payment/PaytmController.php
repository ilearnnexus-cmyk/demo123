<?php

namespace App\Http\Controllers\Api\Payment;

use App\Models\User;
use PaytmWallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\PackageController;

class PaytmController extends Controller
{

    public function index(Request $request)
    {
        $package_id = 0;
        if (isset($request->package_id)) {
            $package_id = $request->package_id;
        }
        if ($request->payment_type) {
            $payment_data = ["package_id" => $package_id, "payment_method" => $request->payment_method, "amount" => $request->amount];

            $transaction = new Transaction();
            $transaction->user_id = auth()->user()->id;
            $transaction->gateway = 'paytm';
            $transaction->payment_type = $request->payment_type;
            $transaction->additional_content = json_encode($payment_data);
            $transaction->save();

            if (auth()->user()->phone != null) {
                $payment = PaytmWallet::with('receive');
                $payment->prepare([
                    'order' => $transaction->id,
                    'user' => auth()->user()->id,
                    'mobile_number' => auth()->user()->phone,
                    'email' => auth()->user()->email,
                    'amount' => $request->amount,
                    'callback_url' => route('api.paytm.callback')
                ]);
                return $payment->receive();
            } else {
                return $this->failure_message('Please add phone number to your profile');
            }
        }
    }

    public function callback(Request $request)
    {
        $transaction = PaytmWallet::with('receive');

        $response = $transaction->response(); // To get raw response as array
        //Check out response parameters sent by paytm here -> http://paywithpaytm.com/developer/paytm_api_doc?target=interpreting-response-sent-by-paytm

        if ($transaction->isSuccessful()) {
            $transaction = Transaction::findOrFail($response['ORDERID']);
            auth()->login(User::findOrFail($transaction->user_id));
            if ($transaction->payment_type == 'package_payment') {
                return (new PackageController)->package_payment_done($transaction->user_id,json_decode($transaction->additional_content, true), json_encode($response));
            } elseif ($transaction->payment_type == 'wallet_payment') {
                auth()->login(User::findOrFail($transaction->user_id));
                return (new WalletController)->wallet_payment_done($transaction->user_id,json_decode($transaction->additional_content, true), json_encode($response));
            }
            return response()->json(['result' => false, 'message' => translate("Payment failed")]);
        }
    }
}
