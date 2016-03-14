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

//$openId = 'oVL1qwFi3nd5D2uM4mV6FHeaaEbk';
$mockUser = new Overtrue\Socialite\User([
    'id' => 'oVL1qwFi3nd5D2uM4mV6FHeaaEbk',
    'nickname' => 'D.C',
    'avatar' => 'http://wx.qlogo.cn/mmopen/klGHad9cnXwZkCYUuwhruNoB7Q5Xwc8TtyhcJGAQCaUJ8WWY8m4D9vNQo0Giby22cPeXmgEyMssEibQhNQRSXibEgliaiaY0UyqgR/0',
]);
session(['wechat.oauth_user' => $mockUser]);

$globalMiddleware = [
    'web',
    // 'wechat.oauth',
];

Route::group(['middleware' => $globalMiddleware], function () {
    Route::get('/', 'HomeController@index');
    Route::get('activity/{activity}/information', 'InformationController@index');
    Route::post('activity/{activity}/information', 'InformationController@store');
    Route::get('activity/{activity}/join', 'ActivityController@join');
    Route::resource('activity', 'ActivityController', ['only' => ['index', 'show']]);
});
