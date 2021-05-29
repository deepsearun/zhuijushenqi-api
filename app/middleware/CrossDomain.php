<?php
declare (strict_types=1);

namespace app\middleware;

/**
 * 跨域请求支持
 */
class CrossDomain
{
    protected $header = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Max-Age' => 1800,
        'Access-Control-Allow-Methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With',
    ];

    /**
     * 允许跨域请求
     * @access public
     * @param $request
     * @param \Closure $next
     * @param array|null $header
     * @return mixed
     */
    public function handle($request, \Closure $next, ?array $header = [])
    {
        $header = !empty($header) ? array_merge($this->header, $header) : $this->header;

        return $next($request)->header($header);
    }
}
