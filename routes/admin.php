<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PermissionGroupController;


Route::
        namespace('Auth')->group(function () {
            Route::controller('LoginController')->group(function () {
                Route::get('/', 'showLoginForm')->name('login');
                Route::post('/', 'login')->name('login');
                Route::get('logout', 'logout')->name('logout')->middleware('admin');
            });

            // Admin Password Reset
            Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
                Route::get('reset', 'showLinkRequestForm')->name('reset');
                Route::post('reset', 'sendResetCodeEmail');
                Route::get('code-verify', 'codeVerify')->name('code.verify');
                Route::post('verify-code', 'verifyCode')->name('verify.code');
            });

            Route::controller('ResetPasswordController')->group(function () {
                Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
                Route::post('password/reset/change', 'reset')->name('password.change');
            });
        });

Route::middleware('admin')->group(function () {

    Route::controller('PermissionGroupController')->middleware(['permission:manage_admins'])->group(function () {
        // ->name('manage_admins')
        Route::get('permission_group/list', [PermissionGroupController::class, 'index'])->name('manage_admins.permission_groups');
        Route::get('permission_group/create', [PermissionGroupController::class, 'create'])->name('manage_admins.permission_group.create');
        Route::post('permission_group/store', [PermissionGroupController::class, 'store'])->name('manage_admins.permission_group.store');
        Route::get('permission_group/{id}/edit', [PermissionGroupController::class, 'edit'])->name('manage_admins.permission_group.edit');
        Route::post('permission_group/{id}/update', [PermissionGroupController::class, 'update'])->name('manage_admins.permission_group.update');
        Route::get('admins', [PermissionGroupController::class, 'getAdmins'])->name('manage_admins.admins');
        Route::post('admin/store', [PermissionGroupController::class, 'adminStore'])->name('manage_admins.admin.store');
        Route::post('admin/update/{id}', [PermissionGroupController::class, 'adminUpdate'])->name('manage_admins.admin.update');
    });

    Route::controller('OrderController')->group(function () {
        Route::name('order.')->prefix('order')->group(function () {
            Route::get('open', 'open')->name('open');
            Route::get('history', 'history')->name('history');
            
            Route::get('/{order}/edit', 'edit')->name('edit');
            Route::post('/{order}/update', 'update')->name('update');
            Route::post('/{order}/delete', 'destroy')->name('delete');
        });
        Route::get('trade/history', 'tradeHistory')->name('trade.history');
    });

    Route::controller('AdminController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications', 'notifications')->name('notifications');
        Route::get('notification/read/{id}', 'notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request-report', 'requestReport')->name('request.report');
        Route::post('request-report', 'reportSubmit');

        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
        Route::get('load/data', 'loadData')->name('load.data');
    });

    // Currency Manager
    Route::controller('CurrencyController')->name('currency.')->prefix('currency')->group(function () {
        Route::get('/crypto', 'crypto')->name('crypto');
        Route::get('/fiat', 'fiat')->name('fiat');
        Route::get('/all', 'all')->name('all');
        Route::post('/save/{id?}', 'save')->name('save');
        Route::post('/status/{id?}', 'status')->name('status');
        Route::post('/import', 'import')->name('import');
    });



    Route::controller('CoinPairController')->name('coin.pair.')->prefix('coin/pair')->group(function () {
        Route::get('/list', 'list')->name('list');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::get('/create', 'create')->name('create');
        Route::post('/save/{id?}', 'save')->name('save');
        Route::post('/status/{id?}', 'status')->name('status');
    });

    // Market Manager
    Route::controller('MarketController')->name('market.')->prefix('market')->group(function () {
        Route::get('/list', 'list')->name('list');
        Route::post('/save/{id?}', 'save')->name('save');
        Route::post('/status/{id?}', 'status')->name('status');
    });
    // Users Manager
    Route::controller('ManageUsersController')->name('users.')->prefix('users')->group(function () {
        Route::get('/', 'allUsers')->name('all');
        Route::get('active', 'activeUsers')->name('active');
        Route::get('banned', 'bannedUsers')->name('banned');
        Route::get('email-verified', 'emailVerifiedUsers')->name('email.verified');
        Route::get('list', 'list')->name('list');
        Route::get('email-unverified', 'emailUnverifiedUsers')->name('email.unverified');
        Route::get('mobile-unverified', 'mobileUnverifiedUsers')->name('mobile.unverified');
        Route::get('kyc-unverified', 'kycUnverifiedUsers')->name('kyc.unverified');
        Route::get('kyc-pending', 'kycPendingUsers')->name('kyc.pending');
        Route::get('mobile-verified', 'mobileVerifiedUsers')->name('mobile.verified');

        Route::get('detail/{id}', 'detail')->name('detail');
        Route::get('kyc-data/{id}', 'kycDetails')->name('kyc.details');
        Route::post('kyc-approve/{id}', 'kycApprove')->name('kyc.approve');
        Route::post('kyc-reject/{id}', 'kycReject')->name('kyc.reject');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('add-sub-balance/{id}', 'addSubBalance')->name('add.sub.balance');
        Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('status/{id}', 'status')->name('status');

        Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');
        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');
        Route::post('toggle-favorite/{id}', 'toggleFavorite')->name('toggle.favorite');
        Route::post('toggle-type/{id}', 'toggleType')->name('toggle.type');
        Route::post('update-comment/{id}', 'updateComment')->name('update.comment');
        Route::post('update-owner/{id}', 'updateOwner')->name('update.owner');
        Route::post('update-status/{id}', 'updateSalesStatus')->name('update.owner');

        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('import', 'importView')->name('import.view');
        Route::post('import', 'import')->name('import');
        Route::get('export', 'export')->name('export');

        Route::get('sales-status', 'salesStatus')->name('sales.status');
        Route::get('sales-status/create', 'salesStatusCreateView')->name('sales.status.create');
        Route::post('sales-status/store', 'salesStatusStore')->name('sales.status.store');
        Route::get('sales-status/{status}/edit', 'salesStatusEditView')->name('sales.status.edit');
        Route::post('sales-status/{status}/update', 'salesStatusUpdate')->name('sales.status.update');
        Route::post('sales-status/{status}/delete', 'salesStatusDelete')->name('sales.status.delete');

        Route::post('{user}', 'destroy')->name('delete');
    });

    // Subscriber
    Route::controller('SubscriberController')->prefix('subscriber')->name('subscriber.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('send-email', 'sendEmailForm')->name('send.email');
        Route::post('remove/{id}', 'remove')->name('remove');
        Route::post('send-email', 'sendEmail')->name('send.email');
        Route::post('remove/{id}/{order_side}/{amount}/{closed_price}/{profit}', 'orderDelete')->name('remove.order');
    });

    // Deposit Gateway
    Route::name('gateway.')->prefix('gateway')->group(function () {

        // Automatic Gateway
        Route::controller('AutomaticGatewayController')->prefix('automatic')->name('automatic.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{code}', 'update')->name('update');
            Route::post('remove/{id}', 'remove')->name('remove');
            Route::post('status/{id}', 'status')->name('status');
        });


        // Manual Methods
        Route::controller('ManualGatewayController')->prefix('manual')->name('manual.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('new', 'create')->name('create');
            Route::post('new', 'store')->name('store');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
        });
    });

    //manage p2p
    Route::namespace("P2P")->prefix('p2p')->name('p2p.')->group(function () {
        Route::controller('PaymentMethodController')->name('payment.method.')->prefix('payment-method')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/edit/{id?}', 'edit')->name('edit');
            Route::post('/save/{id?}', 'save')->name('save');
            Route::post('/status/{id?}', 'status')->name('status');
        });

        Route::controller('PaymentWindowController')->name('payment.window.')->prefix('payment-window')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/save/{id?}', 'save')->name('save');
            Route::post('/status/{id?}', 'status')->name('status');
        });

        Route::controller('AdController')->name('ad.')->prefix('ad')->group(function () {
            Route::get('/', 'index')->name('index');
        });
        Route::controller('TradeController')->name('trade.')->prefix('trade')->group(function () {
            Route::get('/details/{id}', 'details')->name('details');
            Route::post('message/save/{id}', 'messageSave')->name('message.save');
            Route::post('complete/{id}/{action}', 'complete')->name('complete');
            Route::get('{scope}', 'index')->name('index');
            
            
        });
    });



    // DEPOSIT SYSTEM
    Route::controller('DepositController')->prefix('deposit')->name('deposit.')->group(function () {
        Route::get('/', 'deposit')->name('list');
        Route::get('pending', 'pending')->name('pending');
        Route::get('rejected', 'rejected')->name('rejected');
        Route::get('approved', 'approved')->name('approved');
        Route::get('successful', 'successful')->name('successful');
        Route::get('initiated', 'initiated')->name('initiated');
        Route::get('details/{id}', 'details')->name('details');
        Route::post('reject', 'reject')->name('reject');
        Route::post('approve/{id}', 'approve')->name('approve');
    });


    // WITHDRAW SYSTEM
    Route::name('withdraw.')->prefix('withdraw')->group(function () {

        Route::controller('WithdrawalController')->group(function () {
            Route::get('pending', 'pending')->name('pending');
            Route::get('approved', 'approved')->name('approved');
            Route::get('rejected', 'rejected')->name('rejected');
            Route::get('log', 'log')->name('log');
            Route::get('details/{id}', 'details')->name('details');
            Route::post('approve', 'approve')->name('approve');
            Route::post('reject', 'reject')->name('reject');
        });


        // Withdraw Method
        Route::controller('WithdrawMethodController')->prefix('method')->name('method.')->group(function () {
            Route::get('/', 'methods')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('create', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('edit/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
        });
    });

    // Report
    Route::controller('ReportController')->prefix('report')->name('report.')->group(function () {
        Route::get('transaction', 'transaction')->name('transaction');
        Route::get('login/history', 'loginHistory')->name('login.history');
        Route::get('login/ipHistory/{ip}', 'loginIpHistory')->name('login.ipHistory');
        Route::get('notification/history', 'notificationHistory')->name('notification.history');
        Route::get('email/detail/{id}', 'emailDetails')->name('email.details');
    });


    // Admin Support
    Route::controller('SupportTicketController')->prefix('ticket')->name('ticket.')->group(function () {
        Route::get('/', 'tickets')->name('index');
        Route::get('pending', 'pendingTicket')->name('pending');
        Route::get('closed', 'closedTicket')->name('closed');
        Route::get('answered', 'answeredTicket')->name('answered');
        Route::get('view/{id}', 'ticketReply')->name('view');
        Route::post('reply/{id}', 'replyTicket')->name('reply');
        Route::post('close/{id}', 'closeTicket')->name('close');
        Route::get('download/{ticket}', 'ticketDownload')->name('download');
        Route::post('delete/{id}', 'ticketDelete')->name('delete');
    });


    // Language Manager
    Route::controller('LanguageController')->prefix('language')->name('language.')->group(function () {
        Route::get('/', 'langManage')->name('manage');
        Route::post('/', 'langStore')->name('manage.store');
        Route::post('delete/{id}', 'langDelete')->name('manage.delete');
        Route::post('update/{id}', 'langUpdate')->name('manage.update');
        Route::get('edit/{id}', 'langEdit')->name('key');
        Route::post('import', 'langImport')->name('import.lang');
        Route::post('store/key/{id}', 'storeLanguageJson')->name('store.key');
        Route::post('delete/key/{id}', 'deleteLanguageJson')->name('delete.key');
        Route::post('update/key/{id}', 'updateLanguageJson')->name('update.key');
        Route::get('get-keys', 'getKeys')->name('get.key');
    });

    Route::controller('GeneralSettingController')->group(function () {
        // General Setting
        Route::get('general-setting', 'index')->name('setting.index');
        Route::post('general-setting', 'update')->name('setting.update');

        // Pusher Configuration
        Route::get('pusher-configuration', 'pusherConfiguration')->name('setting.pusher.configuration');
        Route::post('pusher-configuration', 'pusherConfigurationUpdate')->name('setting.pusher.configuration');

        // trading view chart
        Route::get('chart/setting', 'chartSetting')->name('setting.chart');
        Route::post('chart/setting', 'chartSettingUpdate')->name('setting.chart');


        //charge settting
        Route::get('charge-setting', 'chargeSetting')->name('setting.charge');
        Route::post('charge-setting', 'chargeSettingUpdate')->name('setting.charge');

        Route::get('setting/social/credentials', 'socialiteCredentials')->name('setting.socialite.credentials');
        Route::post('setting/social/credentials/update/{key}', 'updateSocialiteCredential')->name('setting.socialite.credentials.update');
        Route::post('setting/social/credentials/status/{key}', 'updateSocialiteCredentialStatus')->name('setting.socialite.credentials.status.update');

        //configuration
        Route::get('setting/system-configuration', 'systemConfiguration')->name('setting.system.configuration');
        Route::post('setting/system-configuration', 'systemConfigurationSubmit');

        // Logo-Icon
        Route::get('setting/logo-icon', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css', 'customCss')->name('setting.custom.css');
        Route::post('custom-css', 'customCssSubmit');

        //Cookie
        Route::get('cookie', 'cookie')->name('setting.cookie');
        Route::post('cookie', 'cookieSubmit');

        //maintenance_mode
        Route::get('maintenance-mode', 'maintenanceMode')->name('maintenance.mode');
        Route::post('maintenance-mode', 'maintenanceModeSubmit');


        //wallet setting
        Route::get('wallet-setting', 'walletSetting')->name('wallet.setting');
        Route::post('wallet-setting', 'walletSettingSubmit');
    });

    Route::controller('CronConfigurationController')->name('cron.')->prefix('cron')->group(function () {
        Route::get('index', 'cronJobs')->name('index');
        Route::post('store', 'cronJobStore')->name('store');
        Route::post('update', 'cronJobUpdate')->name('update');
        Route::post('delete/{id}', 'cronJobDelete')->name('delete');
        Route::get('schedule', 'schedule')->name('schedule');
        Route::post('schedule/store', 'scheduleStore')->name('schedule.store');
        Route::post('schedule/status/{id}', 'scheduleStatus')->name('schedule.status');
        Route::get('schedule/pause/{id}', 'schedulePause')->name('schedule.pause');
        Route::get('schedule/logs/{id}', 'scheduleLogs')->name('schedule.logs');
        Route::post('schedule/log/resolved/{id}', 'scheduleLogResolved')->name('schedule.log.resolved');
        Route::post('schedule/log/flush/{id}', 'logFlush')->name('log.flush');
    });


    //KYC setting
    Route::controller('KycController')->group(function () {
        Route::get('kyc-setting', 'setting')->name('kyc.setting');
        Route::post('kyc-setting', 'settingUpdate');
    });

    //Notification Setting
    Route::name('setting.notification.')->controller('NotificationController')->prefix('notification')->group(function () {
        //Template Setting
        Route::get('global', 'global')->name('global');
        Route::post('global/update', 'globalUpdate')->name('global.update');
        Route::get('templates', 'templates')->name('templates');
        Route::get('template/edit/{id}', 'templateEdit')->name('template.edit');
        Route::post('template/update/{id}', 'templateUpdate')->name('template.update');

        //Email Setting
        Route::get('email/setting', 'emailSetting')->name('email');
        Route::post('email/setting', 'emailSettingUpdate');
        Route::post('email/test', 'emailTest')->name('email.test');

        //SMS Setting
        Route::get('sms/setting', 'smsSetting')->name('sms');
        Route::post('sms/setting', 'smsSettingUpdate');
        Route::post('sms/test', 'smsTest')->name('sms.test');
    });

    // Plugin
    Route::controller('ExtensionController')->prefix('extensions')->name('extensions.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });
    //currency data provider
    Route::controller('CurrencyDataProviderController')->prefix('currency/data/provider')->name('currency.data.provider.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('default/{id}', 'default')->name('default');
    });


    //System Information
    Route::controller('SystemController')->name('system.')->prefix('system')->group(function () {
        Route::get('info', 'systemInfo')->name('info');
        Route::get('server-info', 'systemServerInfo')->name('server.info');
        Route::get('optimize', 'optimize')->name('optimize');
        Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
        Route::get('system-update', 'systemUpdate')->name('update');
        Route::post('update-upload', 'updateUpload')->name('update.upload');
    });

    Route::controller('ReferralController')->name('referrals.')->prefix('referrals')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'update')->name('update');
        Route::get('status/{id}', 'status')->name('status');
    });


    // SEO
    Route::get('seo', 'FrontendController@seoEdit')->name('seo');


    // Frontend
    Route::name('frontend.')->prefix('frontend')->group(function () {

        Route::controller('FrontendController')->group(function () {
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'templatesActive')->name('templates.active');
            Route::get('frontend-sections/{key}', 'frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
            Route::post('remove/{id}', 'remove')->name('remove');
        });

        // Page Builder
        Route::controller('PageBuilderController')->group(function () {
            Route::get('manage-pages', 'managePages')->name('manage.pages');
            Route::post('manage-pages', 'managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete/{id}', 'managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'manageSectionUpdate')->name('manage.section.update');
        });
    });
});
