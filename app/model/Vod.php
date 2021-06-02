<?php


namespace app\model;

use app\common\lib\HttpCurl;
use think\facade\Cache;

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
            ->order('vod_time,vod_hits_week desc')->select();
        $total = $this->whereDay('vod_time')->count();
        return $this->showResArr($data, $total);
    }

    /**
     * 获取热门电影
     * @return array
     */
    public function getHotMovie(): array
    {
        $map[] = ['type_id|type_id_1', '=', 1];
        $map[] = ['vod_year', '>=', date('Y') - 1];
        $data = $this->listField()->where($map)->page($this->page, $this->pageSize)
            ->order('vod_time,vod_hits desc')->select();
        $total = $this->where($map)->count();
        return $this->showResArr($data, $total);
    }

    /**
     * 获取热门电视剧
     * @return array
     */
    public function getHotTv(): array
    {
        $map[] = ['type_id|type_id_1', '=', 2];
        $map[] = ['vod_year', '>=', date('Y') - 1];
        $data = $this->listField()->where($map)->page($this->page, $this->pageSize)
            ->order('vod_time,vod_hits desc')->select();
        $total = $this->where($map)->count();
        return $this->showResArr($data, $total);
    }

    /**
     * 获取热门综艺
     * @return array
     */
    public function getHotVariety(): array
    {
        $map[] = ['type_id|type_id_1', '=', 3];
        $map[] = ['vod_year', '=', date('Y')];
        $data = $this->listField()->where($map)->page($this->page, $this->pageSize)
            ->order('vod_time,vod_hits desc')->select();
        $total = $this->where($map)->count();
        return $this->showResArr($data, $total);
    }

    /**
     * 获取热门动漫
     * @return array
     */
    public function getHotComic(): array
    {
        $map[] = ['type_id|type_id_1', '=', 4];
        $map[] = ['vod_year', '>=', date('Y') - 1];
        $data = $this->listField()->where($map)->page($this->page, $this->pageSize)
            ->order('vod_time,vod_hits desc')->select();
        $total = $this->where($map)->count();
        return $this->showResArr($data, $total);
    }

    /**
     * 获取热门影视列表
     * @return array
     */
    public function getHotList(): array
    {
        $data = $this->listField()->page($this->page, $this->pageSize)
            ->order('vod_hits,vod_year desc')->select();
        $total = $this->count();
        return $this->showResArr($data, $total);
    }

    /**
     * 获取影片详情
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     */
    public function detail(): array
    {
        $vod_id = input('vod_id');
        $data = $this->with(['parentType' => function ($query) {
            $query->field('type_id,type_name');
        }, 'type' => function ($query) {
            $query->field('type_id,type_name');
        }])->find($vod_id)->toArray();
        $data = $this->parsePlayData($data);
        // 统计影片热度
        $this->countHits('vod', $vod_id);
        return $data;
    }

    /**
     * 创建搜索词记录 如果已存在 查询次数 +1
     * @param $keyword
     * @return bool
     */
    public function saveSearchWord($keyword): bool
    {
        if (empty($keyword)) return false;
        $key = 'keywordSearch_' . md5($keyword);
        $lock = cache($key . '_lock');
        if ($lock) return false;
        $redis = redis();
        $redis->sAdd('search_set', $key);
        $isSearch = $redis->hKeys($key);
        if (!$isSearch) {
            $redis->hMset($key, [
                'word' => $keyword,
                'num' => 1,
                'time' => time()
            ]);
        } else {
            $redis->hIncrBy($key, 'num', 1);
        }
        cache($key . '_lock', 1, 5);
        return true;
    }

    /**
     * 获取热搜关键词
     * @return array
     */
    public function getHotSearchWords(): array
    {
        $arrays = [];
        $redis = redis();
        $searchArr = $redis->sMembers('search_set');
        if (empty($searchArr)) return $arrays;
        foreach ($searchArr as $key) {
            $arrays[] = [
                'num' => $redis->hGet($key, 'num'),
                'keyword' => $redis->hGet($key, 'word'),
                'time' => $redis->hGet($key, 'time')
            ];
        }
        $arrays = multiArraySort($arrays, 'num', 'desc');
        $arrays = array_slice($arrays, 0, 15);
        return $this->showResArr($arrays);
    }

    /**
     * 搜索
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function search(): array
    {
        $keyword = urldecode(input('keyword'));
        $this->saveSearchWord($keyword);
        $total = $this->where('vod_name|vod_actor|vod_director', 'like', '%' . $keyword . '%')
            ->count();
        $data = $this->listField()->with(['parentType' => function ($query) {
            $query->field('type_id,type_name');
        }])->where('vod_name|vod_actor|vod_director', 'like', '%' . $keyword . '%')
            ->page($this->page, $this->pageSize)
            ->order('vod_hits desc')
            ->select();
        return $this->showResArr($data, $total);
    }
}