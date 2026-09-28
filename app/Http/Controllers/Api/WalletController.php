<?php

namespace App\Http\Controllers\Api;

use App\Models\Wallet;
use App\Models\WalletWithdrawRequest;
use Illuminate\Http\Request;
use App\Http\Resources\WalletResource;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\Payment\PaytmController;
use App\Http\Resources\WalletWithdrawRequestResource;
use App\Http\Controllers\Api\Payment\PaypalController;
use App\Http\Controllers\Api\Payment\StripeController;
use App\Http\Controllers\Api\Payment\PaystackController;
use App\Http\Controllers\Api\Payment\RazorpayController;
use App\Http\Controllers\Api\Payment\InstamojoController;
use App\Models\User;

class WalletController extends Controller
{
    public function wallet_balance()
    {
        $data['wallet_balance'] = single_price(auth()->user()->balance);
        return $this->response_data($data);
       
    }
    public function index()
    {
        $wallets = Wallet::where('user_id', auth()->user()->id)->latest()->paginate(10); 
        return WalletResource::collection($wallets)->additional([
                'result' => true
            ]);
    }
    
    public function recharge(Request $request)
    {
        $this->validate($request,[
            'amount'=>'required',
            'payment_method'=>'required'
        ]);
        
        $request->payment_type =  'wallet_payment';
        $request->amount =  $request->amount;
        $request->payment_method =  $request->payment_method;
            
        if ($request->payment_method == 'paypal') {
            $paypal = new PaypalController;
            return $paypal->pay($request);
        }
        elseif ($request->payment_method == 'instamojo') {
            $instamojo = new InstamojoController;
            return $instamojo->pay($request);
        }
        elseif ($request->payment_method == 'stripe') {
            $stripe = new StripeController;
            return $stripe->stripe($request);
        }
        elseif ($request->payment_method == 'razorpay') {
            $razorpay = new RazorpayController;
            return $razorpay->pay($request);
        }
        elseif ($request->payment_method == 'paystack') {
            $paystack = new PaystackController;
            return $paystack->redirectToGateway($request);
        }
        elseif ($request->payment_method == 'paytm') {
            $paytm = new PaytmController;
            return $paytm->index($request);
        }
        elseif ($request->payment_method == 'manual_payment_1' || $request->payment_method == 'manual_payment_2') {
            $reciept = null;
            if ($request->hasFile('reciept')) {
                $reciept = upload_api_file($request->file('reciept'));
            }
            Wallet::create($request->only('amount', 'payment_details', 'transaction_id', 'payment_method') + [
                'user_id'         => auth()->user()->id,
                'offline_payment' => 1,
                'reciept'         => $reciept,
            ]);
            return $this->success_message('Payment completed');
        }
    }
    
    public function wallet_payment_done($user_id,$payment_data, $payment_details)
    {
        $user = User::find($user_id);
        $user->balance = $user->balance + $payment_data['amount'];
        $user->save();

        $wallet                  = new Wallet;
        $wallet->user_id         = $user->id;
        $wallet->amount          = $payment_data['amount'];
        $wallet->payment_method  = $payment_data['payment_method'];
        $wallet->payment_details = $payment_details;
        $wallet->save();

        return response()->json(['result' => true, 'message' => translate("Payment completed")]);
    }

    public function wallet_withdraw_request_history()
    {
        if (addon_activation('referral_system')) {
            $wallet_withdraw_requests = WalletWithdrawRequest::latest()->where('user_id', auth()->user()->id)->paginate(10);
            return WalletWithdrawRequestResource::collection($wallet_withdraw_requests)->additional([
                    'result' => true
                ]);
        }
        return $this->failure_message('You are not authorized to access!!');
    }

    public function wallet_withdraw_request_store(Request $request)
    {
        if (addon_activation('referral_system')) {
            if (auth()->user()->balance >= $request->amount) {
                WalletWithdrawRequest::create($request->only('amount', 'details') + [
                    'user_id' => auth()->user()->id
                ]);
    
                $user = auth()->user();
                $user->balance = $user->balance - $request->amount;
                $user->save();
    
                return $this->success_message('Wallet Withdraw Request Sent Successfully');
            }
            return $this->failure_message('Insufficient Balance!!');
        }
        return $this->failure_message('You are not authorized to access!!');
    }
}
