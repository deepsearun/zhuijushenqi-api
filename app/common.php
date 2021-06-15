<?php
// 应用公共文件
use app\common\lib\BaseException;

/**
 * API异常输出
 * @param string $msg 错误信息
 * @param int $errorCode 错误代码
 * @param int $code 状态码
 * @throws BaseException
 */
function ApiException($msg = '接口异常', $errorCode = 999, $code = 400)
{
    throw new BaseException([
        'code' => $code,
        'msg' => $msg,
        'errorCode' => $errorCode
    ]);
}

/**
 * 字符串转义
 * @param $string
 * @param int $force
 * @param bool $strip
 * @return array|string
 */
function daddslashes($string, $force = 0, $strip = FALSE)
{
    !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
    if (!MAGIC_QUOTES_GPC || $force) {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = daddslashes($val, $force, $strip);
            }
        } else {
            $string = addslashes($strip ? stripslashes($string) : $string);
        }
    }
    return $string;
}

/**
 * 验证链接是否携带http协议
 * @param $url
 * @return false|int
 */
function checkProtocol($url)
{
    $preg = "/^http(s)?:\\/\\/.+/";
    return preg_match($preg, $url);
}

/**
 * 替换远程地址
 * @param $url
 * @return array|string|string[]
 */
function replaceRemoteUrl($url)
{
    return str_replace('mac://', config('api.remote_protocol'), $url);
}

/**
 * 获取时间戳 毫秒级
 * @return float
 */
function getMillisecond(): float
{
    list($s1, $s2) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}

if (!function_exists('redis')) {
    /**
     * 获取redis操作句柄
     * @return object|null
     */
    function redis()
    {
        return \think\facade\Cache::store('redis')->handler();
    }
}

/**
 * 多维数组通过键 进行数组排序
 * @param array $array 排序的数组
 * @param $key mixed 用来排序的键名
 * @param string $type 排序类型 大小写不敏感 desc or asc
 * @return array
 */
function multiArraySort(array $array, $key, string $type = 'asc'): array
{
    // 判断排序类型
    $sortType = strtolower($type) == 'asc' ? SORT_ASC : SORT_DESC;

    foreach ($array as $row_array) {
        if (!is_array($row_array)) return [];

        $key_array[] = $row_array[$key];
    }

    if (!array_multisort($key_array, $sortType, $array)) return [];

    return $array;
}

/**
 * 解析字符串为数组
 * @param string $rule
 * @param string $str
 * @return array
 */
function explodeByRule(string $rule, string $str): array
{
    if (empty($str)) return [];
    return explode($rule, $str);
}

/**
 * 随机颜色
 * @return string
 */
function randColor()
{
    $str = '0123456789ABCDEF';

    $estr = '#';

    $len = strlen($str);

    for ($i = 1; $i <= 6; $i++) {
        $num = rand(0, $len - 1);

        $estr = $estr . $str[$num];

    }

    return $estr;

}

