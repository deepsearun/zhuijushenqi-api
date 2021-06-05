<?php

return [
    // 客户端密钥
    'app_key' => 'deepsea2021',
    // 本地图片存储地址 一般是苹果cms地址 http://开头 /结尾
    'image_url' => 'https://v.2oc.cc/',
    // 播放器别名
    'player_name' => [
        'bjm3u8' => [
            'name' => '八戒资源',
            'online' => true,
            'parse_url' => 'https://parse.2oc.cc/?url='
        ],
        'tkm3u8' => [
            'name' => '天空资源',
            'online' => true,
            'parse_url' => 'https://parse.2oc.cc/?url='
        ],
        'kbm3u8' => [
            'name' => '快播资源',
            'online' => true,
            'parse_url' => 'https://parse.2oc.cc/?url='
        ],
        'dbm3u8' => [
            'name' => '百度资源',
            'online' => true,
            'parse_url' => 'https://parse.2oc.cc/?url='
        ],
        'languang' => [
            'name' => '蓝光资源',
            'online' => false,
            'parse_url' => 'https://j.languang.wfss100.com/?url='
        ],
        'languang2' => [
            'name' => '蓝光资源2',
            'online' => false,
            'parse_url' => 'https://j.languang.wfss100.com/?url='
        ]
    ],
    // 前端配置
    'viewsConfig' => [
        // 防封模式 开启后关闭视频播放功能 点击影视将直接分享。
        'check' => false
    ]
];