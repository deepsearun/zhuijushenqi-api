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
 * 获取时间戳 毫秒级
 * @return float
 */
function getMillisecond(): float
{
    list($s1, $s2) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}