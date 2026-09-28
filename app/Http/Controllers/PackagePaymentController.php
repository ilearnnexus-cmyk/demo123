<?php

namespace App\Http\Controllers;

use App\Notifications\DbStoreNotification;
use Notification;
use Illuminate\Http\Request;
use App\Utility\EmailUtility;
use App\Utility\SmsUtility;
use App\Models\PackagePayment;
use App\Models\Member;
use App\Models\Package;
use App\Models\Wallet;
use App\Models\User;
use Auth;
use Kutia\Larafirebase\Facades\Larafirebase;
use Session;

class PackagePaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:show_package_payments'])->only('index');
        $this->middleware(['permission:manage_package_manual_payemnts'])->only('show');
        $this->middleware(['permission:view_package_payment_invoice'])->only('package_payment_invoice_admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = 10;
        $package_payments = PackagePayment::latest()->paginate($per_page);
        return view('admin.package_payments.index', compact('package_payments', 'per_page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user(); 
        $data['amount']         = $request->amount;
        $data['package_id']     = $request->package_id;
        $data['payment_method'] = $request->payment_option;

        $request->session()->put('payment_type', 'package_payment');
        $request->session()->put('payment_data', $data);

        if ($request->payment_option == 'paypal') {
            $paypal = new PaypalController();
            return $paypal->pay();
        } elseif ($request->payment_option == 'instamojo') {
            $instamojo = new InstamojoController();
            return $instamojo->pay($request);
        } elseif ($request->payment_option == 'stripe') {
            $stripe = new StripeController();
            return $stripe->pay();
        } elseif ($request->payment_option == 'razorpay') {
            $razorpay = new RazorpayController();
            return $razorpay->pay($request);
        } elseif ($request->payment_option == 'paystack') {
            $paystack = new PaystackController();
            return $paystack->redirectToGateway($request);
        } elseif ($request->payment_option == 'paytm') {
            $paystack = new PaytmController();
            return $paystack->index($request);
        } elseif ($request->payment_option == 'wallet') {
            if ($user->balance < $request->amount) {
                flash(translate('You do not have enough balance.'))->error();
                return back();
            } else {
                $user->balance = $user->balance - $request->amount;
                $user->save(); 
                return $this->package_payment_done($request->session()->get('payment_data'), null);
            }
        } elseif ($request->payment_option == 'manual_payment_1' || $request->payment_option == 'manual_payment_2') {
            $package_payment = new PackagePayment();
            $package_payment->payment_code = date('ymd-His');
            $package_payment->user_id = $user->id;
            $package_payment->package_id = $request->package_id;
            $package_payment->payment_method = 'manual_payment';
            $package_payment->payment_status = 'Due';
            $package_payment->amount = $request->amount;
            $package_payment->payment_details = '';
            $package_payment->offline_payment = 1;
            $package_payment->custom_payment_name = get_setting($request->payment_option . '_name');
            $package_payment->custom_payment_transaction_id = $request->transaction_id;
            $package_payment->custom_payment_proof = $request->payment_proof;
            $package_payment->custom_payment_details = $request->payment_details;

            $package_payment->save();

            // Package Payment Store Notification for member
            try {
                $notify_type = 'package_purchase';
                $id = unique_notify_id();
                $notify_by = $user->id;
                $info_id = $package_payment->id;
                $message = $user->first_name . ' ' . $user->last_name . translate('has been purchased a new package. Payment Code: ') . $package_payment->payment_code;
                $route = route('package-payments.index');

                // fcm
                if (get_setting('firebase_push_notification') == 1) {
                    $fcmTokens = User::where('user_type', 'admin')
                        ->whereNotNull('fcm_token')
                        ->pluck('fcm_token')
                        ->toArray();
                    Larafirebase::withTitle($notify_type)
                        ->withBody($message)
                        ->sendMessage($fcmTokens);
                }
                // end of fcm

                Notification::send(User::where('user_type', 'admin')->first(), new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
            } catch (\Exception $e) {
                // dd($e);
            }

            // Payment approval email send to member
            if ($user->email != null && get_email_template('package_purchase_email', 'status')) {
                EmailUtility::package_purchase_email($user, $package_payment);
            }

            Session::forget('payment_data');
            Session::forget('payment_type');

            flash(translate('Payment completed'))->success();
            return redirect()->route('package_payment.invoice', $package_payment->id);
        }
    }

    public function package_payment_done($payment_data, $payment_details)
    {
        $user = auth()->user();

        $package_payment = new PackagePayment();
        $package_payment->payment_code = date('ymd-His');
        $package_payment->user_id = $user->id;
        $package_payment->package_id = $payment_data['package_id'];
        $package_payment->payment_method = $payment_data['payment_method'];
        $package_payment->payment_status = 'Paid';
        $package_payment->amount = $payment_data['amount'];
        $package_payment->payment_details = $payment_details;
        $package_payment->offline_payment = 2;
        $package_payment->save();

        $member = Member::where('user_id', $user->id)->first();
        $package = Package::where('id', $payment_data['package_id'])->first();
        $member->current_package_id = $package->id;
        $member->remaining_interest = $member->remaining_interest + $package->express_interest;
        $member->remaining_photo_gallery = $member->remaining_photo_gallery + $package->photo_gallery;
        $member->remaining_contact_view = $member->remaining_contact_view + $package->contact;
        $member->remaining_profile_image_view = $member->remaining_profile_image_view + $package->profile_image_view;
        $member->remaining_gallery_image_view = $member->remaining_gallery_image_view + $package->gallery_image_view;
        $member->auto_profile_match = $package->auto_profile_match;
        $member->package_validity = date('Y-m-d', strtotime($member->package_validity . ' +' . $package->validity . 'days'));

        if ($member->save()) {
            $user->membership = 2;
            $user->save();

            if (addon_activation('referral_system') && $user->referred_by != null && $user->referral_comission == 0) {
                // For Referred by user
                $reffered_by = User::where('id', $user->referred_by)->first();
                $wallet = new Wallet();
                $wallet->user_id = $reffered_by->id;
                $wallet->amount = get_setting('referred_by_user_commission');
                $wallet->payment_method = 'reffered_commission';
                $wallet->payment_details = '';
                $wallet->referral_user = $user->id;
                $wallet->save();

                $reffered_by->balance = $reffered_by->balance + get_setting('referred_by_user_commission');
                $reffered_by->save();

                $user->referral_comission = 1;
                $user->save();
            }

            // Package Payment Store Notification for member
            try {
                $notify_type = 'package_purchase';
                $id = unique_notify_id();
                $notify_by = $user->id;
                $info_id = $package_payment->id;
                $message = $user->first_name . ' ' . $user->last_name . translate('has been purchased a new package. Payment Code: ') . $package_payment->payment_code;
                $route = route('package-payments.index');

                // fcm
                if (get_setting('firebase_push_notification') == 1) {
                $fcmTokens = User::where('user_type', 'admin')
                    ->whereNotNull('fcm_token')
                    ->pluck('fcm_token')
                    ->toArray();
                Larafirebase::withTitle($notify_type)
                    ->withBody($message)
                    ->sendMessage($fcmTokens);
                }
                // end of fcm

                Notification::send(User::where('user_type', 'admin')->first(), new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
            } catch (\Exception $e) {
                // dd($e);
            }

            // Payment approval email send to member
            if ($user->email != null && get_email_template('package_purchase_email', 'status')) {
                EmailUtility::package_purchase_email($user, $package_payment);
            }
        }

        Session::forget('payment_data');
        Session::forget('payment_type');

        flash(translate('Payment completed'))->success();
        return redirect()->route('package_payment.invoice', $package_payment->id);
    }

    public function package_purchase_history(Request $request)
    {
        $package_payments = PackagePayment::latest()
            ->where('user_id', Auth::user()->id)
            ->paginate(10);
        return view('frontend.member.package_payment_history', compact('package_payments'));
    }

    public function package_payment_invoice($id)
    {
        $payment = PackagePayment::findOrFail($id);
        return view('frontend.member.payment_invoice', compact('payment'));
    }

    public function package_payment_invoice_admin($id)
    {
        $payment = PackagePayment::findOrFail($id);
        return view('admin.package_payments.payment_invoice', compact('payment'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $package_payment = PackagePayment::findOrFail($id);
        return view('admin.package_payments.payment_details', compact('package_payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function manual_payment_accept($id)
    {
        $package_payment = PackagePayment::findOrFail($id);
        $user = User::where('id', $package_payment->user_id)->first();
        $member = Member::where('user_id', $user->id)->first();
        $package = Package::where('id', $package_payment->package_id)->first();
        $member->current_package_id = $package->id;
        $member->remaining_interest = $member->remaining_interest + $package->express_interest;
        $member->remaining_photo_gallery = $member->remaining_photo_gallery + $package->photo_gallery;
        $member->remaining_contact_view = $member->remaining_contact_view + $package->contact;
        $member->remaining_profile_image_view = $member->remaining_profile_image_view + $package->profile_image_view;
        $member->remaining_gallery_image_view = $member->remaining_gallery_image_view + $package->gallery_image_view;
        $member->auto_profile_match = $package->auto_profile_match;
        $member->package_validity = date('Y-m-d', strtotime($member->package_validity . ' +' . $package->validity . 'days'));

        if ($member->save()) {
            $package_payment->payment_status = 'Paid';
            $package_payment->save();

            $user->membership = 2;
            $user->save();

            if (addon_activation('referral_system') && $user->referred_by != null && $user->referral_comission == 0) {
                // For Referred by user
                $reffered_by = User::where('id', $user->referred_by)->first();
                $wallet = new Wallet();
                $wallet->user_id = $reffered_by->id;
                $wallet->amount = get_setting('referred_by_user_commission');
                $wallet->payment_method = 'reffered_commission';
                $wallet->referral_user = $user->id;
                $wallet->save();

                $reffered_by->balance = $reffered_by->balance + get_setting('referred_by_user_commission');
                $reffered_by->save();

                $user->referral_comission = 1;
                $user->save();
            }

            // Payment approval Store Notification for member
            try {
                $notify_type = 'payemnt_approval';
                $id = unique_notify_id();
                $notify_by = $user->id;
                $info_id = $package_payment->id;
                $message = translate('Your payment for package ') . $package_payment->package->name . translate(' has been approved. Payment Id: ') . $package_payment->payment_code;
                $route = route('package_purchase_history');

                // fcm
                if (get_setting('firebase_push_notification') == 1) {
                $fcmTokens = User::where('id', $user->id)
                    ->whereNotNull('fcm_token')
                    ->pluck('fcm_token')
                    ->toArray();
                Larafirebase::withTitle($notify_type)
                    ->withBody($message)
                    ->sendMessage($fcmTokens);
                }
                // end of fcm

                Notification::send($user, new DbStoreNotification($notify_type, $id, $notify_by, $info_id, $message, $route));
            } catch (\Exception $e) {
                // dd($e);
            }

            // Payment approval email send to member
            if ($user->email != null && get_email_template('manual_payment_approval_email', 'status')) {
                EmailUtility::manual_payment_approval_email($user, $package_payment);
            }

            // Payment approval SMS send to member
            if ($user->phone != null && addon_activation('otp_system') && get_sms_template('manual_payment_approval', 'status')) {
                SmsUtility::manual_payment_approval($user, $package_payment);
            }
        }

        flash(translate('Payemnt accepted successfully.'))->success();
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
