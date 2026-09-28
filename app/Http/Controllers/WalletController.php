<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\InstamojoController;
use App\Http\Controllers\PaystackController;
use App\Models\Wallet;
use App\Models\User;
use Auth;
use Session;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:wallet_transaction_history'])->only('wallet_transaction_history_admin');
        $this->middleware(['permission:offline_wallet_recharge_requests'])->only('manual_wallet_recharge_requests');
    }

    public function index()
    {
        if(!get_setting('wallet_system')){
            return back();
        }
        
        $wallets = Wallet::where('user_id', Auth::user()->id)->latest()->paginate(9);
        return view('frontend.member.wallet.index', compact('wallets'));
    }

    public function wallet_recharge_methods()
    {
      return view('frontend.member.wallet.recharge_methods');
    }

    public function show($id)
    {
      $wallet_payment    = Wallet::findOrFail($id);
      return view('admin.wallet.wallet_payment_details', compact('wallet_payment'));
    }

    public function recharge(Request $request)
    {
        $data['amount'] = $request->amount;
        $data['payment_method'] = $request->payment_option;

        // dd($data);

        $request->session()->put('payment_type', 'wallet_payment');
        $request->session()->put('payment_data', $data);

        if ($request->payment_option == 'paypal') {
            $paypal = new PaypalController;
            return $paypal->pay();
        }
        elseif ($request->payment_option == 'instamojo') {
            $instamojo = new InstamojoController;
            return $instamojo->pay($request);
        }
        elseif ($request->payment_option == 'stripe') {
            $stripe = new StripeController;
            return $stripe->pay();
        }
        elseif ($request->payment_option == 'razorpay') {
            $razorpay = new RazorpayController;
            return $razorpay->pay($request);
        }
        elseif ($request->payment_option == 'paystack') {
            $paystack = new PaystackController;
            return $paystack->redirectToGateway($request);
        }
        elseif ($request->payment_option == 'paytm') {
            $paytm = new PaytmController;
            return $paytm->index();
        }
        elseif ($request->payment_option == 'aamarpay') {
            $paytm = new AamarpayController;
            return $paytm->pay();
        }
        elseif ($request->payment_option == 'sslcommerz') {
            $paytm = new SslcommerzController;
            return $paytm->pay($request);
        }
        elseif ($request->payment_option == 'manual_payment_1' || $request->payment_option == 'manual_payment_2') {
          $user = Auth::user();

          $wallet = new Wallet;
          $wallet->user_id = $user->id;
          $wallet->amount = $request->amount;
          $wallet->payment_method = $request->payment_option;
          $wallet->payment_details = $request->payment_details;
          $wallet->offline_payment = 1;
          $wallet->reciept = $request->payment_proof;
          $wallet->transaction_id = $request->transaction_id;
          $wallet->save();

          Session::forget('payment_data');
          Session::forget('payment_type');

          flash(translate('Payment completed'))->success();
          return redirect()->route('wallet.index');
        }
    }

    public function wallet_payment_done($payment_data, $payment_details)
    {
        $user = auth()->user();
        $user->balance = $user->balance + $payment_data['amount'];
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $payment_data['amount'];
        $wallet->payment_method = $payment_data['payment_method'];
        $wallet->payment_details = $payment_details;
        $wallet->save();

        Session::forget('payment_data');
        Session::forget('payment_type');

        flash(translate('Payment completed'))->success();
        return redirect()->route('wallet.index');
    }

    public function manual_wallet_recharge_requests()
    {
        $wallets = Wallet::latest()->where('offline_payment', 1)->paginate(10);
        return view('admin.wallet.manual_recharge_requests', compact('wallets'));
    }

    public function wallet_manual_payment_accept($id)
    {
        $wallet = Wallet::findOrFail($id);
        $wallet->approval = 1;
        $user = $wallet->user;
        $user->balance = $user->balance + $wallet->amount;
        $user->save();
        $wallet->save();
        flash(translate('Wallet Manual Payment Accepted Successfully'))->success();
        return redirect()->route('manual_wallet_recharge_requests');
    }

    public function wallet_transaction_history_admin(Request $request)
    {
        $user_id = null;
        $date_range = null;

        if($request->user_id) {
            $user_id = $request->user_id;
        }

        $users_with_wallet = User::whereIn('id', function($query) {
            $query->select('user_id')->from(with(new Wallet)->getTable());
        })->get();

        $wallet_history = Wallet::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $wallet_history = $wallet_history->where('created_at', '>=', $date_range1[0]);
            $wallet_history = $wallet_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($user_id){
            $wallet_history = $wallet_history->where('user_id', '=', $user_id);
        }

        $wallets = $wallet_history->paginate(10);
        return view('admin.wallet.transaction_history', compact('wallets', 'users_with_wallet', 'user_id', 'date_range'));
    }
}
