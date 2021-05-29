<?php

namespace app\model;

/**
 * Class Art
 * @author deepsea <top@52e.cc>
 * @package app\model
 */
class Art extends Base
{
    protected $pk = 'art_id';

    protected $hidden = ['art_tpl', 'art_time_add', 'art_jumpurl'];

    protected $globalScope = ['status'];

    /**
     * 定义全局的查询范围
     * @param $query
     */
    public function scopeStatus($query)
    {
        $query->where('art_status', 1);
    }

    /**
     * 获取器 轮播图
     * @param $value
     * @param $data
     * @return string
     */
    public function getArtPicSlideAttr($value, $data): string
    {
        return $this->checkJoinPicUrl($value);
    }

    /**
     * 获取器 图片
     * @param $value
     * @param $data
     * @return string
     */
    public function getArtPicAttr($value, $data): string
    {
        return $this->checkJoinPicUrl($value);
    }

    /**
     * 获取轮播图
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getSlider(): array
    {
        $data = $this->field('art_id,art_name as title,art_pic_slide as image')
            ->where('art_level', 9)
            ->select();
        return $this->showResArr($data);
    }

    /**
     * 获取最新资讯
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNew(): array
    {
        $data = $this->page($this->page, $this->pageSize)
            ->order('art_id desc')->select();
        $total = $this->count();
        return $this->showResArr($data, $total);
    }

}