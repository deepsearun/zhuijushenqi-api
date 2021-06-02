<?php

namespace app\controller\v1;

use app\model\Vod;
use app\validate\VodValidate;

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
     * @throws \app\common\lib\BaseException
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

    /**
     * 获取热门电视剧
     * @return \think\response\Json
     */
    public function getHotTv(): \think\response\Json
    {
        $data = (new Vod())->getHotTv();
        return self::showResCode('获取成功', $data);
    }

    /**
     * 获取热门综艺
     * @return \think\response\Json
     */
    public function getHotVariety(): \think\response\Json
    {
        $data = (new Vod())->getHotVariety();
        return self::showResCode('获取成功', $data);
    }

    /**
     * 获取热门动漫
     * @return \think\response\Json
     */
    public function getHotComic(): \think\response\Json
    {
        $data = (new Vod())->getHotComic();
        return self::showResCode('获取成功', $data);
    }

    /**
     * 获取热门列表
     * @return \think\response\Json
     */
    public function getHotList(): \think\response\Json
    {
        $data = (new Vod())->getHotList();
        return self::showResCode('获取成功', $data);
    }

    /**
     * 搜索影片
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function search(): \think\response\Json
    {
        $data = (new Vod())->search();
        return self::showResCode('获取成功', $data);
    }

    /**
     * 获取热门搜索
     * @return \think\response\Json
     */
    public function searchHotWords(): \think\response\Json
    {
        $data = (new Vod())->getHotSearchWords();
        return self::showResCode('获取成功', $data);
    }

    /**
     * 影片详情
     * @return \think\response\Json
     * @throws \app\common\lib\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function detail(): \think\response\Json
    {
        (new VodValidate())->goCheck('detail');
        $data = (new Vod())->detail();
        return self::showResCode('获取成功', $data);
    }
}