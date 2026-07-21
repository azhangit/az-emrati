<?php

//Paytm

use App\Http\Controllers\Payment\PaytmController;
use App\Http\Controllers\Payment\ToyyibpayController;
use App\Http\Controllers\Payment\KhaltiController;


if (class_exists(PaytmController::class)) {
    Route::controller(PaytmController::class)->group(function () {
        Route::get('/paytm/index', 'pay');
        Route::post('/paytm/callback', 'callback')->name('paytm.callback');
    });

    //Admin
    Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
        Route::controller(PaytmController::class)->group(function () {
            Route::get('/paytm_configuration', 'credentials_index')->name('paytm.index');
            Route::post('/paytm_configuration_update', 'update_credentials')->name('paytm.update_credentials');
        });
    });
}

//Toyyibpay
if (class_exists(ToyyibpayController::class)) {
    Route::controller(ToyyibpayController::class)->group(function () {
        Route::get('toyyibpay-status', 'paymentstatus')->name( 'toyyibpay-status');
        Route::post('/toyyibpay-callback', 'callback')->name( 'toyyibpay-callback');
    });
}

//Khalti START
if (class_exists(KhaltiController::class)) {
    Route::any('/khalti/payment/done', [KhaltiController::class,'paymentDone'])->name('khalti.success');
}
