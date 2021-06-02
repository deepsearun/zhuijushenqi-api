<?php

namespace app\validate;

use app\model\Vod;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 通用数据验证 支持验证场景
     * @param string $scene 验证场景
     * @return bool
     * @throws \app\common\lib\BaseException
     */
    public function goCheck(string $scene = ''): bool
    {
        //获取所有请求参数
        $params = input();
        //是否需要验证场景
        $check = $scene ? $this->scene($scene)->check($params) : $this->check($params);
        if (!$check) {
            ApiException($this->getError(), 10000);
        }
        return true;
    }

    /**
     * 判断影片是否存在
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function isVodExist($value, $rule = '', $data = '', $field = '')
    {
        if ($value == 0) return true;
        if (Vod::field('vod_id')->find($value)) {
            return true;
        }
        return "影片不存在";
    }
}