<?php


namespace app\model;

/**
 * Class Vod
 * @author deepsea <top@52e.cc>
 * @package app\model
 */
class Vod extends Base
{
    protected $pk = 'vod_id';

    protected $globalScope = ['status'];

    /**
     * 定义全局的查询范围
     * @param $query
     */
    public function scopeStatus($query)
    {
        $query->where('vod_status', 1);
    }

    /**
     * 关联子分类
     * @return \think\model\relation\HasOne
     */
    public function type(): \think\model\relation\HasOne
    {
        return $this->hasOne('Type', 'type_id', 'type_id');
    }

    /**
     * 关联父分类
     * @return \think\model\relation\HasOne
     */
    public function parentType(): \think\model\relation\HasOne
    {
        return $this->hasOne('Type', 'type_id', 'type_id_1');
    }

    /**
     * 获取器 轮播图
     * @param $value
     * @param $data
     * @return string
     */
    public function getVodPicSlideAttr($value, $data): string
    {
        return $this->checkJoinPicUrl($value);
    }

    /**
     * 获取器 影片图片
     * @param $value
     * @param $data
     * @return string
     */
    public function getVodPicAttr($value, $data): string
    {
        return $this->checkJoinPicUrl($value);
    }

    /**
     * 列表通用 限制返回的字段
     * @return object
     */
    public function listField()
    {
        $field = 'vod_id,type_id,type_id_1,vod_name,vod_class,';
        $field .= 'vod_letter,vod_pic,vod_pic_slide,vod_actor,vod_director,vod_blurb,vod_remarks,';
        $field .= 'vod_hits,vod_hits_day,vod_hits_week,vod_hits_month,';
        $field .= 'vod_area,vod_lang,vod_year,vod_level,vod_score,vod_time,vod_content';
        return $this->field($field)->with('type');
    }

    /**
     * 普通内容 限制返回的字段
     * @return object
     */
    public function baseField()
    {
        $field = 'vod_id,type_id,type_id_1,vod_name,vod_pic,vod_pic_slide,vod_actor,vod_director,';
        $field .= 'vod_blurb,vod_hits,vod_area,vod_lang,vod_year,vod_score,vod_remarks,vod_time';
        return $this->field($field);
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
        $data = $this->field('vod_id,vod_name as title,vod_pic_slide as image')
            ->where('vod_level', 9)
            ->select();
        return $this->showResArr($data);
    }

    /**
     * 获取今日更新
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getTodayUp(): array
    {
        $data = $this->listField()->whereDay('vod_time')
            ->page($this->page, $this->pageSize)
            ->order('vod_time desc')->select();
        $total = $this->whereDay('vod_time')->count();
        return $this->showResArr($data, $total);
    }

    /**
     * 获取热门电影
     * @return array
     */
    public function getHotMovie(): array
    {
        $map = ['type_id' => 1];
        $data = $this->listField()->where($map)->page($this->page, $this->pageSize)
            ->order('vod_hits_day desc')->select();
        $total = $this->where($map)->count();
        return $this->showResArr($data, $total);
    }
}