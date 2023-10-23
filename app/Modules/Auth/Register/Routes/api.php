<?php

declare(strict_types=1);

Route::group(['prefix' => '/', 'middleware' => []], function () {
    Route::post('/register', 'Api\RegisterController@register')->name('api.register.create');
    Route::post('/confirmSMSRegister', 'Api\RegisterController@confirm_sms_code')->name('api.register.confirmSMS');

//    Route::post('/registration/confirm', 'Api\OtpController@register')->name('api.auths.confirm_register');
//        Route::post('/', g'Api\AuthController@store')->name('api.auths.store');
    //    Route::get('/{auth}', 'Api\AuthController@show')->name('api.auths.read');
    //    Route::p ut('/{auth}', 'Api\AuthController@update')->name('api.auths.update');
    //    Route::delete('/{auth}', 'Api\AuthController@destroy')->name('api.auths.delete');
});
