<?php

namespace app\common\lib;

/**
 * Class Sign
 * @author deepsea <top@52e.cc>
 * @package app\common\lib
 */
class Sign
{
    public $key;

    public function __construct()
    {
        $this->key = config('api.app_key');
    }

    /**
     * 生成签名
     * @param $data
     * @param null $key
     * @return string
     */
    public function create($data, $key = null): string
    {
        if (is_null($key)) $key = $this->key;
        if (is_array($data)) $data = http_build_query($data);
        return md5($data . $key);
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para
     * return 去掉空值与签名参数后的新签名参数组
     * @return array
     */
    private function paraFilter($para): array
    {
        $para_filter = [];
        foreach ($para as $key => $val) {
            $filter = ['sign', 's', 'version'];
            if (in_array($key, $filter)) continue;

            $para_filter[$key] = $val;
        }
        return $para_filter;
    }

    /**
     * 数组排序
     * @param $para
     * @return array
     */
    private function sort($para): array
    {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * 验证签名
     * @param $key
     * @return bool|string
     */
    public function verify($key = null)
    {
        if (is_null($key)) $key = $this->key;

        $param = input();

        if (empty($param['sign'])) return false;

        $paramArr = $this->paraFilter($param);
        $paramSort = $this->sort($paramArr);
        $paramStr = http_build_query($paramSort);
        $parseStr = $paramStr . $key;

        if ($param['sign'] != md5($parseStr)) {
            return false;
        }

        return md5($parseStr);
    }

}
