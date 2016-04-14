<?php

return [

    'custom' => [
        'name' => [
            'required' => '请填写姓名',
            'max' => '姓名超过最大允许长度',
        ],
        'tel' => [
            'required' => '请填写手机号码',
            'digits' => '请填写11位手机号码',
        ],
        'num' => [
            'required_if' => '请填写参与人数',
            'between' => '人数超过上限',
        ],
        'date' => [
            'required' => '请填写日期',
            'date_format' => '日期格式不匹配',
            'after' => '日期不在允许范围内',
            'before' => '日期不在允许范围内',
        ],
        'from' => [
            'required_if' => '请填写开始时间',
            'regex' => '开始时间格式不匹配',
            'after' => '开始时间不在允许范围内',
            'before' => '开始时间不在允许范围内',
        ],
        'to' => [
            'required_if' => '请填写结束时间',
            'regex' => '结束时间格式不匹配',
            'after' => '结束时间不在允许范围内',
        ],
        'areas' => [
            'required' => '请选择场地',
        ],
        'card_no' => [
            'required' => '请填写8位卡号',
            'digits' => '请填写8位卡号',
        ],
    ],

];
