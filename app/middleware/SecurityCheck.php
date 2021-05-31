<?php

namespace app\middleware;


use app\common\lib\Sign;

class SecurityCheck
{
    /**
     * 请求生命周期 毫秒
     * @var int
     */
    protected $timeout = 10000;

    /**
     * 请求安全检测
     * @throws \app\common\lib\BaseException
     */
    public function handle($request, \Closure $next)
    {
        // 时间戳超时效验
        $timestamp = input('timestamp', 0, 'intval');
        if ((getMillisecond() - $timestamp) > $this->timeout) {
            ApiException('Invalid request', 998);
        }
        // 请求签名效验
        $signCheck = (new Sign())->verify(config('api.app_key'));
        if (false === $signCheck) {
            ApiException('Invalid request', 997);
        }
        // 重复请求拦截
        if (cache($signCheck)) {
            ApiException('Invalid request', 996);
        }

        cache($signCheck, $signCheck, $this->timeout);

        return $next($request);
    }
}