<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Member;
use App\Models\Wallet;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Utility\EmailUtility;
use App\Models\PackagePayment;
use App\Http\Resources\PackageResource;
use App\Http\Controllers\Api\Controller;
use App\Notifications\DbStoreNotification;
use Illuminate\Support\Facades\Notification;
use Kutia\Larafirebase\Facades\Larafirebase;
use App\Http\Resources\PackagePaymentResource;
use App\Http\Controllers\Api\Payment\PaytmController;
use App\Http\Resources\PackagePaymentInvoiceResource;
use App\Http\Controllers\Api\Payment\PaypalController;
use App\Http\Controllers\Api\Payment\StripeController;
use App\Http\Controllers\Api\Payment\PaystackController;
use App\Http\Controllers\Api\Payment\RazorpayController;
use App\Http\Controllers\Api\payment\InstamojoController;

class PackageController extends Controller
{
    public function active_packages()
    {
        $packages = Package::where('active', '1')->get();
        return PackageResource::collection($packages)->additional([
                'result' => true
            ]);
    }

    public function package_details(Request $request)
    {
        $package = Package::find($request->package_id);
        if ($package) {
            return (new PackageResource($package))->additional([
                    'result' => true
                ]);
        }
        return $this->failure_message('sorry!!, Invalid Data.');
    }

    public function package_purchase_history()
    {
        $package_payments = PackagePayment::latest()
            ->where('user_id', auth()->user()->id)
            ->paginate(10);
        return PackagePaymentResource::collection($package_payments)->additional([
                'result' => true
            ]);
    }

    public function package_purchase_history_invoice(Request $request)
    {
        $payment = PackagePayment::find($request->package_payment_id);
        if ($payment) {
            return (new PackagePaymentInvoiceResource($payment))->additional([
                'result' => true
            ]);
        }
        return $this->failure_message('sorry!!, Invalid Data.');
    }

    public function package_purchase(Request $request)
    {
        $user = auth()->user(); 

        $request->payment_type   = 'package_payment';
        $request->amount         =  $request->amount;
        $request->package_id     =  $request->package_id;
        $request->payment_method =  $request->payment_method;

        if ($request->payment_method == 'paypal') {
            $paypal = new PaypalController();
            return $paypal->pay($request);
        } elseif ($request->payment_method == 'instamojo') {
            $instamojo = new InstamojoController();
            return $instamojo->pay($request);
        } elseif ($request->payment_method == 'stripe') {
            $stripe = new StripeController();
            return $stripe->stripe($request);
        } elseif ($request->payment_method == 'razorpay') {
            $razorpay = new RazorpayController();
            return $razorpay->pay($request);
        } elseif ($request->payment_method == 'paystack') {
            $paystack = new PaystackController();
            return $paystack->redirectToGateway($request);
        } elseif ($request->payment_method == 'paytm') {
            $paystack = new PaytmController();
            return $paystack->index($request);
        } elseif ($request->payment_method == 'wallet') {
            if ($user->balance < $request->amount) {
                return $this->failure_message('You do not have enough balance.');
            } else {
                $user->balance = $user->balance - $request->amount;
                $user->save(); 
                return $this->package_payment_done($request->session()->get('payment_data'), null);
            }
        } elseif ($request->payment_method == 'manual_payment_1' || $request->payment_method == 'manual_payment_2') {
            $payment_proof = null;
            if ($request->hasFile('payment_proof')) {
                $payment_proof = upload_api_file($request->file('payment_proof'));
            }
            $package_payment = new PackagePayment();
            $package_payment->payment_code = date('ymd-His');
            $package_payment->user_id = $user->id;
            $package_payment->package_id = $request->package_id;
            $package_payment->payment_method = 'manual_payment';
            $package_payment->payment_status = 'Due';
            $package_payment->amount = $request->amount;
            $package_payment->payment_details = '';
            $package_payment->offline_payment = 1;
            $package_payment->custom_payment_name = get_setting($request->payment_method . '_name');
            $package_payment->custom_payment_transaction_id = $request->transaction_id;
            $package_payment->custom_payment_proof = $payment_proof;
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
            return response()->json(['result' => true, 'message' => translate("Payment completed")]);
        }
    }

    public function package_payment_done($user_id, $payment_data, $payment_details)
    {
        $user = User::where('id',$user_id)->first();

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
        return response()->json(['result' => true, 'message' => translate("Payment completed")]);
    }
}
