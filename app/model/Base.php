<?php

namespace app\model;

use think\facade\Cache;
use think\facade\Db;
use think\Model;

class Base extends Model
{
    public $page = 0;
    public $pageSize = 10;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->page = (int)input('page', 1,);
        $this->pageSize = (int)input('pageSize', 10);
    }

    /**
     * 验证是否需要拼接图片URL
     * @param $url
     * @return string
     */
    protected function checkJoinPicUrl($url): string
    {
        if (checkProtocol($url)) return $url;
        return config('api.image_url') . trim($url, '/');
    }

    /**
     * 解析播放数据
     * @param array $data
     * @return array
     */
    public function parsePlayData(array $data): array
    {
        $playFrom = explodeByRule('$$$', trim($data['vod_play_from'], '$$$'));
        $downFrom = explodeByRule('$$$', trim($data['vod_down_from'], '$$$'));
        $playList = $this->parsePlayAndDownloadUrl(trim($data['vod_play_url'], '$$$'));
        $downList = $this->parsePlayAndDownloadUrl(trim($data['vod_down_url'], '$$$'));
        if (count($playFrom) > count($playList)) {
            $playFrom = array_splice($playFrom, 1);
        }
        if (empty($downFrom) || empty($downList)) {
            $data['vod_play_from'] = $playFrom;
            $data['vod_play_url'] = $playList;
            unset($playList, $playList);
            return $data;
        }
        $data['vod_down_from'] = $downFrom;
        $data['vod_down_url'] = $downList;
        $data['vod_play_from'] = array_merge($playFrom, $downFrom);
        $data['vod_play_url'] = array_merge($playList, $downList);
        unset($downList, $downFrom, $playList, $playList);
        return $data;
    }

    /**
     * 解析播放和下载链接
     * @param string $str
     * @return array
     */
    public function parsePlayAndDownloadUrl(string $str): array
    {
        $res = [];
        $arr = [];
        if (empty($str)) return [];
        $groupArr = explodeByRule('$$$', $str);
        foreach ($groupArr as $item) {
            $arr[] = explode('#', $item);
        }
        for ($i = 0; $i <= count($arr) - 1; $i++) {
            foreach ($arr[$i] as $row) {
                $ars = explode('$', $row);
                if (!array_key_exists(0, $ars) || !array_key_exists(1, $ars)) continue;
                $res[$i][] = [
                    'episode' => $ars[0] ?? '',
                    'src' => $ars[1] ?? ''
                ];
            }
        }
        return $res;
    }

    /**
     * 统计人气 当天 本周 本月 总数
     * @param string $name
     * @param int $id
     * @param int $step
     * @return bool
     * @throws \think\db\exception\DbException
     */
    public function countHits(string $name, int $id, int $step = 1): bool
    {
        $lock = 'lock_count_' . $name . '_hits_' . $id;
        $step = mt_rand(1, $step * mt_rand(1, 10));
        if (cache($lock)) return false;
        $field = [
            'day' => $name . '_hits_day',
            'week' => $name . '_hits_week',
            'month' => $name . '_hits_month'
        ];
        $setCache = function ($time) use ($id, $field) {
            $expire = 86400;
            $key = $field[$time] . '_' . $id;
            if ($time == 'week') $expire *= 7;
            if ($time == 'month') $expire *= 30;
            return Cache::set($key, 1, $expire);
        };
        $nameId = $name . '_id';
        foreach ($field as $key => $row) {
            if (cache($row . '_' . $id)) { // +1
                Db::name($name)->where($nameId, $id)->inc($row, $step)->update();
            } else { // 不存在 清空点击数 并设置缓存
                Db::name($name)->where($nameId, $id)->update([$row => 0]);
                $setCache($key);
            }
        }
        Db::name($name)->where($nameId, $id)->inc($name . '_hits', $step)->update();
        // 设置锁
        cache($lock, 1, 5);
        return true;
    }

    /**
     * 列表统一数据格式
     * @param mixed $list
     * @param int $total
     * @return array
     */
    public function showResArr($list = [], int $total = 0): array
    {
        return [
            'list' => $list,
            'total' => !$total ? count($list) : $total,
            'page' => $this->page,
            'pageSize' => $this->pageSize
        ];
    }
}