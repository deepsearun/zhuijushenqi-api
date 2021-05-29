<?php

namespace app\controller\v1;

use app\BaseController;

class CommonController extends BaseController
{
    /**
     * Api统一返回格式
     * @param string $msg
     * @param array|mixed $data
     * @param int $code
     * @return \think\response\Json
     */
    public static function showResCode($msg = '未知', $data = [], $code = 200): \think\response\Json
    {
        $res = [
            'msg' => $msg,
            'data' => $data
        ];
        return json($res, $code);
    }


    /**
     * Api统一返回格式 无数据
     * @param string $msg
     * @param int $code
     * @return \think\response\Json
     */
    public static function showResCodeWithOutData(string $msg = '未知', int $code = 200): \think\response\Json
    {
        return self::showResCode($msg, [], $code);
    }
}