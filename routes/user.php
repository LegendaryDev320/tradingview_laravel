<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::get('left-market-data', function (Request $request) {
        $pageTitle = 'Left Market Data';

        return view('templates.basic.user.left_market_data', compact('pageTitle'));
    })->name('left.market.data');

    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->name('logout')->middleware('auth');
    });

    Route::namespace('Web3')->prefix('web3')->name('web3.')->group(function () {
        Route::controller("MetamaskController")->name('metamask.login.')->prefix('metamask-login')->group(function () {
            Route::any('message', 'message')->name('message');
            Route::post('verify', 'verify')->name('verify');
        });
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register')->middleware('registration.status');
        Route::post('check-mail', 'checkUser')->name('checkUser');
    });
    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });
    Route::controller('SocialiteController')->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    //authorization
    Route::namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
    });

    Route::middleware(['check.status'])->group(function () {

        Route::get('user-data', 'User\UserController@userData')->name('data');
        Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

        Route::middleware('registration.complete')->namespace('User')->group(function () {

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');
                Route::get('more/wallet/{skip}', 'wallet')->name('more.wallet');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions', 'transactions')->name('transactions');

                Route::get('attachment-download/{fil_hash}', 'attachmentDownload')->name('attachment.download');
                Route::get('pair/add/to/favorite/{pairSym}', 'addToFavorite')->name('add.pair.to.favorite');
                Route::get('all/currency', 'allCurrency')->name('currency.all');

                Route::get('referrals', 'referrals')->name('referrals');
            });

            Route::controller('OrderController')->group(function () {
                Route::name('order.')->prefix('order')->group(function () {
                    Route::get('open', 'open')->name('open');
                    Route::get('completed', 'completed')->name('completed');
                    Route::get('canceled', 'canceled')->name('canceled');
                    Route::post('cancel/{id}', 'cancel')->name('cancel');
                    Route::post('update/{id}', 'update')->name('update');
                    Route::get('history', 'history')->name('history');
                    Route::post('remove/{id}/{order_side}/{amount}/{closed_price}/{profit}', 'close')->name('close');

                    Route::post('bulk-close', 'bulkClose')->name('bulkClose');
                });
                Route::get('trade/history', 'tradeHistory')->name('trade.history');
            });

            Route::controller("OrderController")->prefix('order')->name('order.')->group(function () {
                Route::post('save/{symbol}', 'save')->name('save');
            });


            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

            //wallet
            Route::controller('WalletController')->name('wallet.')->prefix('wallet')->group(function () {
                Route::get('list/{type?}', 'list')->name('list');
                Route::post('transfer', 'transfer')->name('transfer');
                Route::post('transfer/to/wallet', 'transferToWallet')->name('transfer.to.other.wallet');
                Route::get('{type}/{currencySymbol}', 'view')->name('view');
            });

            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function () {
                Route::middleware('kyc')->group(function () {
                    Route::post('/store', 'withdrawStore')->name('.money');
                    Route::get('preview', 'withdrawPreview')->name('.preview');
                    Route::post('preview', 'withdrawSubmit')->name('.submit');
                });
                Route::get('history', 'withdrawLog')->name('.history');
            });
        });

        // Payment
        Route::middleware('registration.complete')->prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function () {
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });
    });
});
