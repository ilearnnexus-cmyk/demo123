<?php

/*
|--------------------------------------------------------------------------
| referral Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



//Admin
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
  Route::get('/set-referral-commission', 'ReferralSystemController@set_referral_commission')->name('set_referral_commission');

  Route::get('/referal/users', 'ReferralSystemController@index')->name('referals.users');
  Route::get('/referal/earnings', 'ReferralSystemController@referal_earnings_admin')->name('referal.earnings_admin');

  Route::resource('/wallet-withdraw-requests', 'WalletWithdrawRequestController');
  Route::post('/wallet-withdraw-request-details', 'WalletWithdrawRequestController@wallet_withdraw_request_details')->name('wallet_withdraw_request_details');
  Route::get('/wallet-withdraw-request-accept/{id}', 'WalletWithdrawRequestController@withdraw_request_accept')->name('wallet_withdraw_request.accept');
  Route::get('/wallet-withdraw-request-reject/{id}', 'WalletWithdrawRequestController@withdraw_request_reject')->name('wallet_withdraw_request.reject');

});

Route::group(['middleware' => ['member','verified']], function(){
  Route::get('/referred-users', 'ReferralSystemController@my_referred_users')->name('my_referred_users');
  Route::get('/my-referral-earnings', 'ReferralSystemController@my_referral_earnings')->name('my_referral_earnings');

  Route::get('/wallet-withdraw-request-history', 'WalletWithdrawRequestController@wallet_withdraw_request_history')->name('wallet_withdraw_request_history');
  Route::post('/wallet/withdraw-request-store', 'WalletWithdrawRequestController@store')->name('wallet_withdraw_request.store');

});
