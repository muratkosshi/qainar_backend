<?php

declare(strict_types=1);

Route::group(['prefix' => '/', 'middleware' => []], function () {
    Route::post('/login', 'Api\LoginController@login')->name('api.login.login');
    Route::post('/me', 'Api\LoginController@me')->name('api.login.me');
    Route::post('/refresh_token', 'Api\LoginController@refresh')->name('api.login.refresh');
    Route::post('/logout', 'Api\LoginController@logout')->name('api.login.logout');

//    Route::post('/registration/confirm', 'Api\OtpController@register')->name('api.auths.confirm_register');
//        Route::post('/', g'Api\AuthController@store')->name('api.auths.store');
    //    Route::get('/{auth}', 'Api\AuthController@show')->name('api.auths.read');
    //    Route::p ut('/{auth}', 'Api\AuthController@update')->name('api.auths.update');
    //    Route::delete('/{auth}', 'Api\AuthController@destroy')->name('api.auths.delete');
});
