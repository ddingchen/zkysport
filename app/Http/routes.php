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

$globalMiddleware = [
    'web',
    // 'wechat.oauth',
];

Route::group(['middleware' => $globalMiddleware], function () {
    Route::get('/', function () {
        var_dump(Wechat::user());
        //return session('wechat.oauth_user');
    });
});
