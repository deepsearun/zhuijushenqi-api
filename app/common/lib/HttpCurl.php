<?php

namespace app\common\lib;

/**
 * Class HttpCurl
 * @author deepsea <top@52e.cc>
 * @link https://github.com/deepsearun/phpCurlClass
 */
class HttpCurl
{
    /**
     * 请求URL
     * @var string|array
     */
    public static $url;

    /**
     * 请求方式
     * @var string
     */
    public $method;

    /**
     * 请求数据
     * @var array|string
     */
    public $data;

    /**
     * 配置参数
     * @var array
     */
    public $options = [
        // 请求方式
        'method' => 'get',
        // 请求header头设置
        'httpHeader' => [
            'Accept: application/json',
            'Accept-Encoding: gzip,deflate,sdch',
            'Accept-Language: zh-CN,zh;q=0.8',
            'Connection: close'
        ],
        // 设置cookie
        'cookie' => '',
        // 设置referer
        'referer' => '',
        // 设置请求UA
        'ua' => 'Mozilla/5.0 (Linux; U; Android 4.0.4; es-mx; HTC_One_X Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0',
        // 向网络服务器发送HEAD请求
        'nobody' => 0,
        // 超时时间 ms
        'timeout' => 3000,
        // 将头文件的信息作为数据流输出
        'header' => false
    ];

    /**
     * 批量处理
     * @var bool
     */
    public $multiRequest = false;

    /**
     * 请求结果信息
     * @var array
     */
    protected $resInfo = [];

    /**
     * 响应结果数据
     * @var mixed
     */
    protected $response;

    /**
     * Http constructor.
     * @param string|array $url
     * @throws Exception
     */
    public function __construct($url = '')
    {
        if (!function_exists('curl_init')) { // curl扩展不支持 抛出异常
            throw new Exception('Curl extension not supported');
        }
        if (!empty($url)) self::url($url);
    }

    /**
     * 请求地址
     * @param string|array $url
     * @return $this
     */
    public static function url($url): HttpCurl
    {
        self::$url = $url;
        return new self;
    }

    /**
     * 请求数据
     * @param array|string $data
     * @return $this
     */
    public function data($data): HttpCurl
    {
        if (is_array($data)) $data = http_build_query($data);
        $this->data = $data;
        return $this;
    }

    /**
     * 修改配置信息
     * @param string|array $name
     * @param mixed|$value
     * @return $this
     */
    public function setOptions($name, $value = null): HttpCurl
    {
        if (is_array($name)) {
            $this->options = array_merge($this->options, $name);
        } else {
            $this->options[$name] = $value;
        }
        return $this;
    }

    /**
     * 设置请求方式
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method): HttpCurl
    {
        $this->method = strtolower($method);
        return $this;
    }

    /**
     * get请求
     * @return $this
     */
    public function get(): HttpCurl
    {
        return $this->setMethod('get')->request();
    }

    /**
     * post请求
     * @return $this
     */
    public function post(): HttpCurl
    {
        return $this->setMethod('post')->request();
    }

    /**
     * put请求
     * @return $this
     */
    public function put(): HttpCurl
    {
        return $this->setMethod('put')->request();
    }

    /**
     * delete请求
     * @return $this
     */
    public function delete(): HttpCurl
    {
        return $this->setMethod('delete')->request();
    }


    /**
     * 设置 http header头
     * @param array $httpHeader
     * @return $this
     */
    public function httpHeader(array $httpHeader): HttpCurl
    {
        return $this->setOptions('httpHeader', $httpHeader);
    }

    /**
     * 设置cookie
     * @param string $cookie
     * @return $this
     */
    public function cookie(string $cookie): HttpCurl
    {
        return $this->setOptions('cookie', $cookie);
    }

    /**
     * 设置referer
     * @param string $referer
     * @return $this
     */
    public function referer(string $referer): HttpCurl
    {
        return $this->setOptions('referer', $referer);
    }

    /**
     * 设置ua
     * @param string $ua
     * @return $this
     */
    public function ua(string $ua): HttpCurl
    {
        return $this->setOptions('ua', $ua);
    }

    /**
     * 设置超时时间 ms
     * @param int $timeout
     * @return $this
     */
    public function timeout(int $timeout): HttpCurl
    {
        return $this->setOptions('timeout', $timeout);
    }

    /**
     * 获取响应结果
     * @return mixed
     */
    public function response()
    {
        if (!$this->response) $this->request();
        return $this->response;
    }

    /**
     * 获取请求结果信息
     * @param string $name
     * @return mixed
     */
    public function getResInfo($name = '')
    {
        $this->resInfo['body'] = $this->response();
        return $name ? $this->resInfo[$name] : $this->resInfo;
    }

    /**
     * 获取请求头信息
     * @param bool $isArr 是否输出数组格式，默认字符串
     * @param bool $nobody 是否包含body信息 默认不包含
     * @return mixed
     */
    public function getHeaders($isArr = false, $nobody = true)
    {
        if ($nobody) $this->setOptions('nobody', 1);
        $this->setOptions('header', true);
        $this->request();
        if ($isArr) {
            return $this->headerToArray($this->response);
        }
        return $this->response;
    }

    /**
     * header头信息转数组
     * @param $headerStr
     * @return array
     */
    public function headerToArray($headerStr): array
    {
        $headerArr = explode(PHP_EOL, trim($headerStr));
        $result = [];
        foreach ($headerArr as $k => $item) {
            if ($k == 0) {
                $result[$k] = $item;
            } else {
                $arr = explode(':', $item);
                $result[$arr[0]] = trim($arr[1]);
            }
        }
        return $result;
    }

    /**
     * 获取重定向地址
     * @return string
     */
    public function getRedirectUrl(): string
    {
        $headers = $this->getHeaders();
        preg_match('/location:\s+(.*?)\s+/is', $headers, $match);
        if (empty($match[1])) {
            return '';
        }
        return $match[1];
    }

    /**
     * 设置请求信息
     * @param array $resInfo
     * @return $this
     */
    public function setResInfo(array $resInfo): HttpCurl
    {
        $this->resInfo = $resInfo;
        return $this;
    }

    /**
     * 设置响应结果
     * @param mixed $response
     * @return $this
     */
    public function setResponse($response): HttpCurl
    {
        $this->response = $response;
        return $this;
    }

    /**
     * 获取GET请求URL
     * @param string $url
     * @return string
     */
    public function getUrl($url = ''): string
    {
        if (!$url) $url = self::$url;
        if (!empty($this->data)) {
            $url .= (strpos($url, '?') !== false ? '&' : '?') . $this->data;
        }
        return $url;
    }

    /**
     * 发起请求
     * @return $this
     */
    protected function request(): HttpCurl
    {
        if (is_array(self::$url)) {
            $this->multiRequest = true;
            $this->multiRequest();
        } else {
            $this->singleRequest();
        }

        return $this;
    }

    /**
     * 发起单个请求
     */
    protected function singleRequest()
    {
        $ch = $this->createOneCh();
        $this->setResponse(curl_exec($ch));
        $this->setResInfo(curl_getinfo($ch));
        curl_close($ch);
    }

    /**
     * 发起批量请求
     */
    protected function multiRequest()
    {
        $multiCh = $this->createMultiCh();
        $curlArr = $multiCh['curlArr'];
        $mh = $multiCh['mh'];
        $running = null;
        do {

            $mrc = curl_multi_exec($mh, $running);

        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($running and $mrc == CURLM_OK) {

            if (curl_multi_select($mh) === -1) {
                usleep(100);
            }

            do {

                $mrc = curl_multi_exec($mh, $running);

            } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        }

        foreach ($curlArr as $k => $ch) {

            if ($result[$k] = curl_multi_getcontent($ch)) {
                // 获取请求信息
                $resInfo[] = curl_getinfo($ch);
                //关闭该句柄
                curl_multi_remove_handle($mh, $ch);
            }

        }
        $this->setResponse($result ?? []);
        $this->setResInfo($resInfo ?? []);
        curl_multi_close($mh);
    }

    /**
     * 创建批处理curl句柄
     * @return array
     */
    protected function createMultiCh(): array
    {
        $mh = curl_multi_init();
        $curlArr = [];
        foreach (self::$url as $key => $value) {
            $curlArr[$key] = $this->createOneCh($value);
            curl_multi_add_handle($mh, $curlArr[$key]);
        }
        return ['curlArr' => $curlArr, 'mh' => $mh];
    }

    /**
     * 创建curl句柄
     * @param string $url
     * @return false|resource
     */
    protected function createOneCh($url = '')
    {
        $ch = curl_init();

        if (empty($url)) $url = self::$url;

        $method = empty($this->method) ? $this->options['method'] : $this->method;

        if ($method == 'get') {
            $url = $this->getUrl($url);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->options['httpHeader']);

        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
        }

        if ($method == 'put') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        }

        if ($method == 'delete') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        if ($method != 'get') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
        }

        if ($this->options['header']) {
            curl_setopt($ch, CURLOPT_HEADER, true);
        }

        if (!empty($this->options['cookie'])) {
            curl_setopt($ch, CURLOPT_COOKIE, $this->options['cookie']);
        }

        if (!empty($this->options['referer'])) {
            curl_setopt($ch, CURLOPT_REFERER, $this->options['referer']);
        }

        if (!empty($this->options['ua'])) {
            curl_setopt($ch, CURLOPT_USERAGENT, $this->options['ua']);
        }

        curl_setopt($ch, CURLOPT_NOBODY, $this->options['nobody']);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->options['timeout']);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return $ch;
    }

    /**
     * JSON转换数组
     * @return array
     */
    public function jsonToArray(): array
    {
        if (!$this->multiRequest) return json_decode($this->response, true) ?? [];
        // 批量请求结果处理
        $data = $this->response;
        foreach ($data as &$item) {
            $item = json_decode($item, true);
        }
        return $data;
    }
}