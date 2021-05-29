<?php

namespace app\controller\v1;

use app\model\Vod;

class VodController extends CommonController
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
        $data = (new Vod())->getSlider();
        return self::showResCode('获取成功', $data);
    }

    /**
     * 今日更新
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function todayUp(): \think\response\Json
    {
        $data = (new Vod())->getTodayUp();
        return self::showResCode('获取成功', $data);
    }

    /**
     * 获取热门电影
     * @return \think\response\Json
     */
    public function getHotMovie(): \think\response\Json
    {
        $data = (new Vod())->getHotMovie();
        return self::showResCode('获取成功', $data);
    }
}