<?php

return [
    // 客户端密钥
    'app_key' => 'deepsea2021',
    // 本地图片存储地址 一般是苹果cms地址 http://开头 /结尾
    'image_url' => 'https://v.2oc.cc/',
    // 远程存储替换协议
    'remote_protocol' => 'https://',
    // 播放器别名
    'player_name' => [
        'kbm3u8' => [
            'sort' => 1,
            'name' => '快播资源',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => false
        ],
        'qq' => [
            'sort' => 2,
            'name' => '腾讯视频',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => 'renrenmi2',
        ],
        'youku' => [
            'sort' => 3,
            'name' => '优酷视频',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => 'renrenmi2',
        ],
        'pptv' => [
            'sort' => 4,
            'name' => 'PPTV',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => 'renrenmi2',
        ],
        'qiyi' => [
            'sort' => 5,
            'name' => '爱奇艺',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => 'renrenmi2',
        ],
        'renrenmi' => [
            'sort' => 6,
            'name' => '蓝光影厅',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => 'renrenmi',
        ],
        'alizy' => [
            'sort' => 7,
            'name' => '蓝光影厅2',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => 'renrenmi',
        ],
        'languang' => [
            'sort' => 8,
            'name' => '蓝光影厅3',
            'parse_url' => 'https://j.languang.wfss100.com/?url=',
            'get_url' => 'lg'
        ],
        'languang2' => [
            'sort' => 9,
            'name' => '蓝光影厅4',
            'parse_url' => 'https://j.languang.wfss100.com/?url=',
            'get_url' => 'lg',
        ],
        'letv' => [
            'sort' => 10,
            'name' => '乐视TV',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => 'renrenmi2',
        ],
        'sohu' => [
            'sort' => 11,
            'name' => '搜狐视频',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => 'renrenmi2',
        ],
        'bjm3u8' => [
            'sort' => 12,
            'name' => '八戒资源',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => false
        ],
        'tkm3u8' => [
            'sort' => 13,
            'name' => '天空资源',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => false
        ],
        'dbm3u8' => [
            'sort' => 14,
            'name' => '百度资源',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => false
        ],
        'mgtv' => [
            'sort' => 15,
            'name' => '芒果TV',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => 'renrenmi2',
        ],
        'bilibili' => [
            'sort' => 16,
            'name' => '哔哩哔哩',
            'parse_url' => 'https://parse.2oc.cc/?url=',
            'get_url' => 'renrenmi2',
        ],

    ]
];
