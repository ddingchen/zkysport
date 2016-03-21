<?php

return [
    /**
     * Debug 模式，bool 值：true/false
     *
     * 当值为 false 时，所有的日志都不会记录
     */
    'debug' => true,

    /**
     * 使用 Laravel 的缓存系统
     */
    'use_laravel_cache' => true,

    /**
     * 账号基本信息，请从微信公众平台/开放平台获取
     */
    'app_id' => env('WECHAT_APPID', 'wxc6e9f3ee462287da'), // AppID
    'secret' => env('WECHAT_SECRET', '54ad6e4c8038c6b83ef0521e02e3ffe2'), // AppSecret
    'token' => env('WECHAT_TOKEN', 'zkysport'), // Token
    'aes_key' => env('WECHAT_AES_KEY', ''), // EncodingAESKey

    /**
     * 日志配置
     *
     * level: 日志级别，可选为：
     *                 debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level' => env('WECHAT_LOG_LEVEL', 'debug'),
        'file' => env('WECHAT_LOG_FILE', storage_path('logs/wechat.log')),
    ],

    /**
     * OAuth 配置
     *
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
     */
    'oauth' => [
        'scopes' => array_map('trim', explode(',', env('WECHAT_OAUTH_SCOPES', 'snsapi_userinfo'))),
        'callback' => env('WECHAT_OAUTH_CALLBACK', '/'),
    ],

    /**
     * 微信支付
     */
    'payment' => [
        'merchant_id' => env('WECHAT_PAYMENT_MERCHANT_ID', '1233579702'),
        'key' => env('WECHAT_PAYMENT_KEY', '71C131A37823BEBD7081D3C96BDE6561'),
        'cert_path' => env('WECHAT_PAYMENT_CERT_PATH', 'd:/cert/apiclient_cert.pem'), // XXX: 绝对路径！！！！
        'key_path' => env('WECHAT_PAYMENT_KEY_PATH', 'd:/cert/apiclient_key.pem'), // XXX: 绝对路径！！！！
        // 'device_info'     => env('WECHAT_PAYMENT_DEVICE_INFO', ''),
        // 'sub_app_id'      => env('WECHAT_PAYMENT_SUB_APP_ID', ''),
        // 'sub_merchant_id' => env('WECHAT_PAYMENT_SUB_MERCHANT_ID', ''),
        // ...
    ],
];
