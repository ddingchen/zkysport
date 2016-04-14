<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

// 服务器验证接口
Route::any('/wechat', 'WechatController@serve');
Route::post('payment/notify', 'PaymentController@notify');
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
 */
$globalMiddleware = [
    'web',
];

// add wechat.oauth middleware if in production env
$isProductionEnv = config('app.env') == 'production';
if ($isProductionEnv) {
    $globalMiddleware[] = 'wechat.oauth';
} else {
    $mockUser = new Overtrue\Socialite\User([
        'id' => 'oVL1qwFi3nd5D2uM4mV6FHeaaEbk',
        'nickname' => 'D.C-Test',
        'avatar' => 'http://wx.qlogo.cn/mmopen/klGHad9cnXwZkCYUuwhruNoB7Q5Xwc8TtyhcJGAQCaUJ8WWY8m4D9vNQo0Giby22cPeXmgEyMssEibQhNQRSXibEgliaiaY0UyqgR/0',
        'token' => new Overtrue\Socialite\AccessToken(['access_token' => 'abc']),
    ]);

    session(['wechat.oauth_user' => $mockUser]);
}

// add user profile autoload middleware
$globalMiddleware[] = 'user.autoload';

Route::group(['middleware' => $globalMiddleware], function () {
    // home
    Route::get('/', 'HomeController@index');

    // activity
    Route::get('activity', ['as' => 'activity', 'uses' => 'ActivityController@index']);
    Route::get('activity/{id}', ['as' => 'activity.show', 'uses' => 'ActivityController@show']);
    Route::get('activity/{id}/join', 'ActivityController@join');
    Route::get('activity/{id}/information', 'InformationController@index');
    Route::post('activity/{id}/information', 'InformationController@store');

    // payment
    Route::get('payment/wxpub', 'PaymentController@payByWxpub');

    // book
    Route::get('sport', 'SportController@index');
    Route::post('sport', 'SportController@book');
    Route::get('sport/attempt', 'SportController@attemptAssignAreaholder');
    Route::post('sport/pay', 'SportController@pay');
    Route::get('sport/{sport}/time', 'SportController@bookingTime');
    Route::get('sport/{sport}/area', 'AreaController@index');
    Route::post('sport/{sport}/area', 'AreaController@store');

    // vip
    // Route::get('vip', 'VipController@index');
    Route::get('vip', 'VipController@index');
    Route::get('vip/create', 'VipController@create');
    Route::get('vip/bind', 'VipController@displayBindForm');
    Route::post('vip/bind', 'VipController@bind');
    Route::get('vip/buy', 'VipController@buy');
    Route::get('vip/{cardno}/charge', 'VipController@displayChargeForm');
    Route::post('vip/{cardno}/charge', 'VipController@charge');
    Route::get('vip/{cardno}', 'VipController@detail');

    // user center
    Route::get('user/vip', 'VipController@vipByUser');

    // history
    Route::get('history/{type}/{sub}', 'HistoryController@index');

    // seller
    Route::group(['middleware' => 'seller.autoload'], function () {
        Route::group(['middleware' => 'seller.auth'], function () {
            Route::get('sell', 'SellController@index');
            Route::any('sell/qr', 'SellController@getQRCode');
            Route::get('sell/sold', 'SellController@getSoldCount');
            Route::get('sell/history', 'SellController@history');
        });
        Route::get('sell/auth', 'SellController@login');
        Route::post('sell/auth', 'SellController@storeName');
    });

});

Route::group(['middleware' => 'web'], function () {
    // Authentication Routes...
    $this->get('login', 'Auth\AuthController@showLoginForm');
    $this->post('login', 'Auth\AuthController@login');
    $this->get('logout', 'Auth\AuthController@logout');

    // Password Reset Routes...
    $this->get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    $this->post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    $this->post('password/reset', 'Auth\PasswordController@reset');
});

Route::get('wxpub/menu', 'WechatController@menu');
