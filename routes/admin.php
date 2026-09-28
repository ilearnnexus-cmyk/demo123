<?php

/*
  |--------------------------------------------------------------------------
  | Admin Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register admin routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::post('/update', 'UpdateController@step0')->name('update');
Route::get('/update/step1', 'UpdateController@step1')->name('update.step1');
Route::get('/update/step2', 'UpdateController@step2')->name('update.step2');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', 'HomeController@admin_login')->name('admin');
});

Route::get('/admin/login', 'HomeController@admin_login')->name('admin.login');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/dashboard', 'HomeController@admin_dashboard')->name('admin.dashboard');

    Route::resource('profile', 'ProfileController');

    // Contact Us page
    Route::resource('/contact-us', 'ContactUsController');
    Route::get('/contact-us/destroy/{id}', 'ContactUsController@destroy')->name('contact-us.delete');

    // Member Manage
    Route::resource('/members', 'MemberController');
    Route::get('/members/member_list/{id}', 'MemberController@index')->name('members.index');
    Route::post('/members/approve', 'MemberController@approve')->name('members.approve');
    Route::post('/members/block', 'MemberController@block')->name('members.block');
    Route::post('/members/blocking_reason', 'MemberController@blocking_reason')->name('members.blocking_reason');
    Route::get('/members/login/{id}', 'MemberController@login')->name('members.login');

    Route::get('/deleted_members', 'MemberController@deleted_members')->name('deleted_members');
    Route::get('/members/destroy/{id}', 'MemberController@destroy')->name('members.destroy');
    Route::get('/restore_deleted_member/{id}', 'MemberController@restore_deleted_member')->name('restore_deleted_member');
    Route::get('/members/permanently_delete/{id}', 'MemberController@member_permanemtly_delete')->name('members.permanently_delete');

    Route::get('/reported-members/{id}', 'ReportedUserController@reported_members')->name('reported_members');
    Route::get('/reported/destroy/{id}', 'ReportedUserController@destroy')->name('report_destrot.destroy');

    Route::get('/member/unapproved-profile-pictures', 'MemberController@unapproved_profile_pictures')->name('unapproved_profile_pictures');
    Route::post('/member/approve_profile_image', 'MemberController@approve_profile_image')->name('approve_profile_image');

    // Bulk member
    Route::get('/member-bulk-add/index', 'MemberBulkAddController@index')->name('member_bulk_add.index');
    Route::get('/download/on-behalf', 'MemberBulkAddController@pdf_download_on_behalf')->name('pdf.on_behalf');
    Route::get('/download/package', 'MemberBulkAddController@pdf_download_package')->name('pdf.package');
    Route::post('/bulk-member-upload', 'MemberBulkAddController@bulk_upload')->name('bulk_member_upload');

    // member's package manage
    Route::post('/members/package_info', 'MemberController@package_info')->name('members.package_info');
    Route::post('/members/get_package', 'MemberController@get_package')->name('members.get_package');
    Route::post('/members/package_do_update/{id}', 'MemberController@package_do_update')->name('members.package_do_update');
    Route::get('/package-payment-invoice/{id}', 'PackagePaymentController@package_payment_invoice_admin')->name('package_payment.invoice_admin');
    Route::post('/members/wallet-balance-update', 'MemberController@member_wallet_balance_update')->name('member.wallet_balance_update');

    // Premium Packages
    Route::resource('/packages', 'PackageController');
    Route::post('/packages/update_package_activation_status', 'PackageController@update_package_activation_status')->name('packages.update_package_activation_status');
    Route::get('/packages/destroy/{id}', 'PackageController@destroy')->name('packages.destroy');

    // package Payments
    Route::resource('package-payments', 'PackagePaymentController');
    Route::get('/manual-payment-accept/{id}', 'PackagePaymentController@manual_payment_accept')->name('manual_payment_accept');

    // Wallet
    Route::get('/wallet-transaction-history', 'WalletController@wallet_transaction_history_admin')->name('wallet_transaction_history_admin');
    Route::get('/manual-wallet-recharge-requests', 'WalletController@manual_wallet_recharge_requests')->name('manual_wallet_recharge_requests');
    Route::get('/wallet-payment-details/{id}', 'WalletController@show')->name('wallet_payment_details');
    Route::get('/wallet-manual-payment-accept/{id}', 'WalletController@wallet_manual_payment_accept')->name('wallet_manual_payment_accept');

    Route::post('/happy-story/update-story-status', 'HappyStoryController@approval_status')->name('happy_story_approval.status');

    //Blog Section
    Route::resource('blog-category', 'BlogCategoryController');
    Route::get('/blog-category/destroy/{id}', 'BlogCategoryController@destroy')->name('blog-category.destroy');
    Route::resource('blog', 'BlogController');
    Route::get('/blog/destroy/{id}', 'BlogController@destroy')->name('blog.destroy');
    Route::post('/blog/change-status', 'BlogController@change_status')->name('blog.change-status');

    // Member profile attributes
    // religions
    Route::resource('/religions', 'ReligionController');
    Route::get('/religions/destroy/{id}', 'ReligionController@destroy')->name('religions.destroy');

    // Caste
    Route::resource('/castes', 'CasteController');
    Route::get('/castes/destroy/{id}', 'CasteController@destroy')->name('castes.destroy');

    // SubCaste
    Route::resource('/sub-castes', 'SubCasteController');
    Route::get('/sub-castes/destroy/{id}', 'SubCasteController@destroy')->name('sub-castes.destroy');

    // Member Language
    Route::resource('member-languages', 'MemberLanguageController');
    Route::get('/member-language/destroy/{id}', 'MemberLanguageController@destroy')->name('member-languages.destroy');

    // Country
    Route::resource('/countries', 'CountryController');
    Route::post('/countries/status', 'CountryController@updateStatus')->name('countries.status');
    Route::get('/countries/destroy/{id}', 'CountryController@destroy')->name('countries.destroy');

    // State
    Route::resource('/states', 'StateController');
    Route::get('/states/destroy/{id}', 'StateController@destroy')->name('states.destroy');

    // City
    Route::resource('/cities', 'CityController');
    Route::get('/cities/destroy/{id}', 'CityController@destroy')->name('cities.destroy');

    // Family Status
    Route::resource('/family-status', 'FamilyStatusController');
    Route::get('/family-status/destroy/{id}', 'FamilyStatusController@destroy')->name('family-status.destroy');

    // Family Value
    Route::resource('/family-values', 'FamilyValueController');
    Route::get('/family-values/destroy/{id}', 'FamilyValueController@destroy')->name('family-values.destroy');

    // On Behalf
    Route::resource('/on-behalf', 'OnBehalfController');
    Route::get('/on-behalf/destroy/{id}', 'OnBehalfController@destroy')->name('on-behalf.destroy');

    Route::resource('marital-statuses', 'MaritalStatusController');
    Route::get('/marital-statuses/destroy/{id}', 'MaritalStatusController@destroy')->name('marital-statuses.destroy');

    // Email Templates
    Route::resource('/email-templates', 'EmailTemplateController');
    Route::post('/email-templates/update', 'EmailTemplateController@update')->name('email-templates.update');

    // Marketing
    Route::get('/newsletter', 'NewsletterController@index')->name('newsletters.index');
    Route::post('/newsletter/send', 'NewsletterController@send')->name('newsletters.send');
    Route::post('/newsletter/test/smtp', 'NewsletterController@testEmail')->name('test.smtp');

    // Language
    Route::resource('/languages', 'LanguageController');
    Route::post('/languages/update_rtl_status', 'LanguageController@update_rtl_status')->name('languages.update_rtl_status');
    Route::post('/languages/key_value_store', 'LanguageController@key_value_store')->name('languages.key_value_store');
    Route::get('/languages/destroy/{id}', 'LanguageController@destroy')->name('languages.destroy');

    // Setting
    Route::resource('/settings', 'SettingController');
    Route::post('/settings/update', 'SettingController@update')->name('settings.update');
    Route::post('/settings/activation/update', 'SettingController@updateActivationSettings')->name('settings.activation.update');
    // Firebase Push Notification Setting
    Route::get('/settings/firebase/fcm', 'SettingController@fcm_settings')->name('settings.fcm');
    Route::post('/settings/firebase/fcm', 'SettingController@fcm_settings_update')->name('settings.fcm.update');

    Route::get('/general-settings', 'SettingController@general_settings')->name('general_settings');
    Route::get('/smtp-settings', 'SettingController@smtp_settings')->name('smtp_settings');

    Route::get('/payment-methods-settings', 'SettingController@payment_method_settings')->name('payment_method_settings');
    Route::post('/payment_method_update', 'SettingController@payment_method_update')->name('payment_method.update');

    Route::get('/third-party-settings', 'SettingController@third_party_settings')->name('third_party_settings');
    Route::post('/third-party-settings/update', 'SettingController@third_party_settings_update')->name('third_party_settings.update');

    Route::get('/social-media-login-settings', 'SettingController@social_media_login_settings')->name('social_media_login');

    Route::get('//member-profile-sections', 'SettingController@member_profile_sections_configuration')->name('member_profile_sections_configuration');

    // env Update
    Route::post('/env_key_update', 'SettingController@env_key_update')->name('env_key_update.update');

    // Currency settings
    Route::resource('currencies', 'CurrencyController');
    Route::post('/currency/update_currency_activation_status', 'CurrencyController@update_currency_activation_status')->name('currency.update_currency_activation_status');
    Route::get('/currency/destroy/{id}', 'CurrencyController@destroy')->name('currency.destroy');

    // website setting
    Route::group(['prefix' => 'website'], function () {
        Route::get('/header_settings', 'SettingController@website_header_settings')->name('website.header_settings');
        Route::get('/footer_settings', 'SettingController@website_footer_settings')->name('website.footer_settings');
        Route::get('/appearances', 'SettingController@website_appearances')->name('website.appearances');
        Route::resource('custom-pages', 'PageController');
        Route::get('/custom-pages/edit/{id}', 'PageController@edit')->name('custom-pages.edit');
        Route::get('/custom-pages/destroy/{id}', 'PageController@destroy')->name('custom-pages.destroy');
    });

    Route::resource('staffs', 'StaffController');
    Route::get('/staffs/destroy/{id}', 'StaffController@destroy')->name('staffs.destroy');

    Route::resource('roles', 'RoleController');
    Route::get('/roles/destroy/{id}', 'RoleController@destroy')->name('roles.destroy');

    // permission add
    Route::post('/roles/add_permission', 'RoleController@add_permission')->name('roles.permission');

    Route::get('/notifications', 'NotificationController@index')->name('admin.notifications');

    Route::get('/system/update', 'SettingController@system_update')->name('system_update');
    Route::get('/system/server-status', 'SettingController@system_server')->name('system_server');

    Route::resource('addons', 'AddonController');
    Route::post('/addons/activation', 'AddonController@activation')->name('addons.activation');

    // uploaded files
    Route::any('/uploaded-files/file-info', 'AizUploadController@file_info')->name('uploaded-files.info');
    Route::resource('/uploaded-files', 'AizUploadController');
    Route::get('/uploaded-files/destroy/{id}', 'AizUploadController@destroy')->name('uploaded-files.destroy');

    Route::get('/cache-cache', 'HomeController@clearCache')->name('cache.clear');
});

?>
