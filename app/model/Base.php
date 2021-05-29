<?php

namespace app\model;

use think\Model;

class Base extends Model
{
    public $page = 0;
    public $pageSize = 10;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->page = input('page', 1);
        $this->pageSize = input('pageSize', 10);
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