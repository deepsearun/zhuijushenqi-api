<?php

namespace app\controller\v1;

use app\model\Art;

class ArtController extends CommonController
{
    /**
     * 获取轮播图
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function slider(): \think\response\Json
    {
        $data = (new Art())->getSlider();
        return self::showResCode('获取成功', $data);
    }

    /**
     * 最新数据列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function new(): \think\response\Json
    {
        $data = (new Art())->getNew();
        return self::showResCode('获取成功', $data);
    }
}