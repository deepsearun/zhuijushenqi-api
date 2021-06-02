<?php
namespace app\validate;


class VodValidate extends BaseValidate
{
    protected $rule = [
        'vod_id' => 'require|number|>:0|isVodExist'
    ];

    protected $scene = [
        'detail' => ['vod_id']
    ];
}