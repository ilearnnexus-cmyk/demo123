<?php

use App\Http\Controllers\API\AddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'Api', 'middleware' => ['app_language']], function () {
    // Authentication
    Route::post('/signup', 'AuthController@signup');
    Route::post('/signin', 'AuthController@signin');
    Route::post('/forgot/password', 'AuthController@forgotPassword');
    Route::post('/verify/code', 'AuthController@verifyCode');
    Route::post('/reset/password', 'AuthController@resetPassword');
    Route::post('social-login', 'AuthController@socialLogin');

    Route::get('/home/slider', 'HomeController@home_slider');
    Route::get('/home/banner', 'HomeController@home_banner');
    Route::get('/home/how-it-works', 'HomeController@home_how_it_works');
    Route::get('/home/trusted-by-millions', 'HomeController@home_trusted_by_millions');
    Route::get('/home/happy-stories', 'HomeController@home_happy_stories');
    Route::get('/home/packages', 'HomeController@home_packages');
    Route::get('/home/reviews', 'HomeController@home_reviews');
    Route::get('/home/blogs', 'HomeController@home_blogs');
    Route::get('/home/premium-members', 'HomeController@home_premium_members');
    Route::get('/home/new-members', 'HomeController@home_new_members');

    Route::get('/home', 'HomeController@home');
    Route::get('/packages', 'PackageController@active_packages');
    Route::post('/package-details', 'PackageController@package_details');
    Route::get('/happy-stories', 'HappyStoryController@happy_stories');
    Route::post('/story-details', 'HappyStoryController@story_details');
    Route::get('/blogs', 'BlogController@all_blogs');
    Route::post('/blog-details', 'BlogController@blog_details');
    Route::post('/contact-us', 'HomeController@contact_us');

    Route::get('/addon-check', 'HomeController@addon_check');
    Route::get('/feature-check', 'HomeController@feature_check');
    Route::get('/app-info', 'HomeController@app_info');
    Route::get('/on-behalf', 'ProfileDropdownController@onbehalf_list');

    //Payment Gateways
    Route::group(['namespace' => 'Payment'], function () {
        //Paypal START
        Route::get('/paypal/payment/done', 'PaypalController@getDone')->name('api.paypal.done');
        Route::get('/paypal/payment/cancel', 'PaypalController@getCancel')->name('api.paypal.cancel');
        //Stripe Start           
        Route::any('/stripe/success', 'StripeController@success')->name('api.stripe.success');
        Route::any('/stripe/cancel', 'StripeController@cancel')->name('api.stripe.cancel');
        // PayStack
        Route::get('/paystack/payment/callback', 'PaystackController@handleGatewayCallback');
        //Paytm
        Route::post('/paytm/callback', 'PaytmController@callback')->name('api.paytm.callback');
        // Razor Pay
        Route::any('razorpay/payment', 'RazorpayController@payment')->name('api.razorpay.payment');
        Route::post('razorpay/success', 'RazorpayController@success')->name('api.razorpay.success');
    });

    Route::post('/logout', 'AuthController@logout')->name('logout')->middleware('auth:sanctum');

    Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
        

        //Payment Gateways
        Route::group(['namespace' => 'Payment'], function () {
            Route::get('payment-types', 'PaymentTypesController@getList');
            //Paypal START
            Route::any('paypal/payment/pay', 'PaypalController@pay')->name('api.paypal.pay');
            //Stripe Start
            Route::any('stripe', 'StripeController@stripe');
            Route::any('/stripe/create-checkout-session', 'StripeController@create_checkout_session')->name('api.stripe.get_token');
            Route::any('/stripe/payment/callback', 'StripeController@callback')->name('api.stripe.callback');
            //Paytm
            Route::get('/paytm/index', 'PaytmController@index');
            // Razor Pay
            Route::any('pay-with-razorpay', 'RazorpayController@payWithRazorpay')->name('api.razorpay.payment');
        });

        // member middleware has removed for api but it exist in web
        Route::group(['prefix' => 'member'], function () {
            //Profile
            Route::get('/public-profile/{id}', 'ProfileController@public_profile');
            Route::get('/profile-settings', 'ProfileController@profile_settings');
            Route::get('/introduction', 'ProfileController@get_introduction');
            Route::get('/get-email', 'ProfileController@get_email');
            Route::post('/introduction-update', 'ProfileController@introduction_update');
            Route::get('/basic-info', 'ProfileController@get_basic_info');
            Route::post('/basic-info/update', 'ProfileController@basic_info_update');
            Route::get('present/address', 'ProfileController@present_address');
            Route::get('permanent/address', 'ProfileController@permanent_address');
            Route::post('/address/update', 'ProfileController@address_update');
            Route::post('/education-status/update', 'EducationController@education_status_update');

            Route::post('/career-status/update', 'CareerController@career_status_update');
            Route::get('/physical-attributes', 'ProfileController@physical_attributes');
            Route::post('/physical-attributes/update', 'ProfileController@physical_attributes_update');
            Route::get('/language', 'ProfileController@member_language');
            Route::post('/language/update', 'ProfileController@member_language_update');
            Route::get('/hobbies-interests', 'ProfileController@hobbies_interest');
            Route::post('/hobbies/update', 'ProfileController@hobbies_interest_update');
            Route::get('/attitude-behavior', 'ProfileController@attitude_behavior');
            Route::post('/attitude-behavior/update', 'ProfileController@attitude_behavior_update');
            Route::get('/residency-info', 'ProfileController@residency_info');
            Route::post('/residency-info/update', 'ProfileController@residency_info_update');
            Route::get('/spiritual-background', 'ProfileController@spiritual_background');
            Route::post('/spiritual-background/update', 'ProfileController@spiritual_background_update');
            Route::get('/life-style', 'ProfileController@life_style');
            Route::post('/life-style/update', 'ProfileController@life_style_update');
            Route::get('/astronomic', 'ProfileController@astronomic_info');
            Route::post('/astronomic/update', 'ProfileController@astronomic_info_update');
            Route::get('/family-info', 'ProfileController@family_info');
            Route::post('/family-info/update', 'ProfileController@family_info_update');
            Route::get('/partner-expectation', 'ProfileController@partner_expectation');
            Route::post('/partner-expectation/update', 'ProfileController@partner_expectation_update');
            Route::post('/change/password', 'ProfileController@password_update');
            Route::post('/contact-info/update', 'ProfileController@contact_info_update');
            Route::post('/account/deactivate', 'ProfileController@account_deactivation');
            Route::post('/account/delete', 'ProfileController@account_delete');
            Route::post('/view-contact-store', 'ProfileController@store_view_contact');
            Route::get('/matched-profile', 'ProfileController@matched_profile');
            // support -ticket
            Route::get('/my-tickets', 'SupportTicketController@my_ticket');
            Route::post('/support-ticket/store', 'SupportTicketController@store');
            Route::get('/support-ticket/categories', 'SupportTicketController@support_ticket_categories');
            Route::post('/ticket-reply', 'SupportTicketController@ticket_reply');

            Route::get('/dashboard', 'HomeController@member_dashboard');
            Route::get('/home-with-login', 'HomeController@home_with_login');
            Route::get('/check-happy-story', 'HappyStoryController@happy_story_check');
            Route::post('/happy-story', 'HappyStoryController@store');
            Route::apiResources([
                'gallery-image' => 'GalleryImageController',
                'career' => 'CareerController',
                'education' => 'EducationController',
                'support-ticket' => 'SupportTicketController',
            ]);

            // Gallery Image View Request
            Route::get('/gallery-image-view-request', 'GalleryImageController@image_view_request');
            Route::post('/gallery-image-view-request', 'GalleryImageController@store_image_view_request');
            Route::post('/gallery-image-view-request/accept', 'GalleryImageController@accept_image_view_request')->name('gallery_image_view_request_accept');
            Route::post('/gallery-image-view-request/reject', 'GalleryImageController@reject_image_view_request')->name('gallery_image_view_request_reject');
            // Profile Image View Request
            Route::get('/profile-picture-view-request', 'ProfileImageController@image_view_request');
            Route::post('/profile-picture-view-request', 'ProfileImageController@store_image_view_request');
            Route::post('/profile-picture-view-request/accept', 'ProfileImageController@accept_image_view_request')->name('gallery_image_view_request_accept');
            Route::post('/profile-picture-view-request/reject', 'ProfileImageController@reject_image_view_request')->name('gallery_image_view_request_reject');


            Route::get('/maritial-status', 'ProfileDropdownController@maritial_status');
            Route::get('/countries', 'ProfileDropdownController@country_list');
            Route::get('/states/{id}', 'ProfileDropdownController@state_list');
            Route::get('/cities/{id}', 'ProfileDropdownController@city_list');
            Route::get('/languages', 'ProfileDropdownController@language_list');
            Route::get('/religions', 'ProfileDropdownController@religion_list');
            Route::get('/casts/{id}', 'ProfileDropdownController@caste_list');
            Route::get('/sub-casts/{id}', 'ProfileDropdownController@sub_caste_list');
            Route::get('/family-values', 'ProfileDropdownController@family_value_list');
            Route::get('/profile-dropdown', 'ProfileDropdownController@profile_dropdown');
            // Route::get('/public-profile','ProfileDropdownController@public_profile');

            //chat routes
            Route::get('/chat-list', 'ChatController@chat_list');
            Route::get('/chat-view/{id}', 'ChatController@chat_view');
            Route::post('/chat-reply', 'ChatController@chat_reply');
            Route::post('/chat/old-messages', 'ChatController@get_old_messages');

            // Member
            Route::get('/member-info/{id}', 'MemberController@member_info');
            Route::get('/package-details', 'MemberController@package_details');
            Route::post('/member-listing', 'MemberController@member_listing');
            Route::get('/ignored-user-list', 'MemberController@ignored_user_list');
            Route::post('/add-to-ignore-list', 'MemberController@add_to_ignore_list');
            Route::post('/remove-from-ignored-list', 'MemberController@remove_from_ignored_list');
            Route::post('/report-member', 'MemberController@report_member');
            // Package
            Route::post('/package-purchase', 'PackageController@package_purchase');
            Route::get('/package-purchase-history', 'PackageController@package_purchase_history');
            Route::post('/package-purchase-history-invoice', 'PackageController@package_purchase_history_invoice');
            // Interest
            Route::get('/my-interests', 'InterestController@my_interests');
            Route::post('/express-interest', 'InterestController@express_interest');
            Route::get('/interest-requests', 'InterestController@interest_requests');
            Route::post('/interest-accept', 'InterestController@accept_interest');
            Route::post('/interest-reject', 'InterestController@reject_interest');
            // Shortlist
            Route::get('/my-shortlists', 'ShortlistController@index');
            Route::post('add-to-shortlist', 'ShortlistController@store');
            Route::post('remove-from-shortlist', 'ShortlistController@remove');
            // Walet
            Route::get('/my-wallet-balance', 'WalletController@wallet_balance');
            Route::get('/wallet', 'WalletController@index');
            Route::post('/wallet-recharge', 'WalletController@recharge');
            Route::get('/wallet-withdraw-request-history', 'WalletController@wallet_withdraw_request_history');
            Route::post('/wallet-withdraw-request-store', 'WalletController@wallet_withdraw_request_store');
            // Referral
            Route::get('/referred-users', 'ReferralController@index');
            Route::get('/referral-code', 'ReferralController@referral_code');
            Route::get('/my-referral-earnings', 'ReferralController@referral_earnings');
            Route::get('/referral-check', 'ReferralController@referral_check');
            // Notifications
            Route::get('/notifications', 'NotificationController@notifications');
            Route::get('/notification/{id}', 'NotificationController@single_notification_read');
            Route::get('/mark-all-as-read', 'NotificationController@mark_all_as_read');
            // Happy tory
            Route::get('/happy-story', 'HappyStoryController@happy_story');
        });
    });
});
