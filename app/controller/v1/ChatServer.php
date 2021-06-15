<?php


namespace app\controller\v1;

use app\Request;
use GatewayWorker\Lib\Gateway;
use think\App;

class ChatServer extends CommonController
{
    /**
     * 初始化registerAddress
     * @param App $app
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
        Gateway::$registerAddress = config('gateway_worker.registerAddress');
    }

    /**
     * 发送群消息
     * @param Request $request
     */
    public function send(Request $request)
    {
        $roomId = intval($request->post('roomId'));
        // 处理返回数据
        $data = $this->resData($request);
        Gateway::sendToGroup($roomId, json_encode($data));
        if ($data['data']['type'] == 'left') { // 暂时离开
            Gateway::leaveGroup($data['client_id'], $roomId);
            return;
        }
        $cacheKey = 'room' . $roomId;
        $saveData = cache($cacheKey) ?? [];
        if (count($saveData) >= 200) { // 聊天记录最多记录200条
            $saveData = array_splice($saveData, 0, 1);
        }
        $saveData[count($saveData)] = $data;

        // 弹幕记录
        $episodeCurrent = input('episodeCurrent', 0);
        $dmDataKey = 'dm' . $roomId . $episodeCurrent;
        $dmData = cache($dmDataKey) ?? [];
        // 记录弹幕 最多3000条
        if (count($dmData) >= 3000) {
            $dmData = array_splice($dmData, 0, 1);
        }
        $dmData[count($dmData)] = [
            'text' => $data['data']['content'],
            'color' => randColor(),
            'time' => intval($data['data']['current'] ?: 1)
        ];
        // 记录聊天记录
        cache($cacheKey, $saveData);
        // 记录弹幕
        cache($dmDataKey, $dmData);
    }

    /**
     * 聊天记录
     * @param Request $request
     * @return \think\response\Json
     */
    public function index(Request $request): \think\response\Json
    {
        $roomId = intval($request->get('roomId'));
        $cacheKey = 'room' . $roomId;
        $data = cache($cacheKey) ?? [];
        return self::showResCode('获取成功', $data);
    }

    /**
     * 获取弹幕
     * @param Request $request
     * @return \think\response\Json
     */
    public function dm(Request $request): \think\response\Json
    {
        $episodeCurrent = input('episodeCurrent', 0);
        $roomId = intval($request->get('roomId'));
        $cacheKey = 'dm' . $roomId . $episodeCurrent;
        $data = cache($cacheKey) ?? [];
        return self::showResCode('获取成功', $data);
    }

    /**
     * 获取在线人数
     * @param Request $request
     * @return \think\response\Json
     */
    public function online(Request $request): \think\response\Json
    {
        $roomId = intval($request->get('roomId'));
        // 处理返回数据
        $count = Gateway::getClientCountByGroup($roomId);
        return self::showResCode('获取成功', ['count' => $count]);
    }

    /**
     * 退出房间
     * @param Request $request
     * @return \think\response\Json
     */
    public function exit(Request $request): \think\response\Json
    {
        $roomId = intval($request->post('roomId'));
        $clientId = $request->post('client_id');
        // 处理返回数据
        $data = $this->resData($request);
        Gateway::closeClient($clientId);
        Gateway::sendToGroup($roomId, json_encode($data));
        return self::showResCodeWithOutData('退出成功');
    }

    /**
     * 加入群聊
     * @param Request $request
     * @return \think\response\Json
     */
    public function bind(Request $request): \think\response\Json
    {
        $roomId = intval($request->post('roomId'));
        $clientId = $request->post('client_id');
        // 处理返回数据
        $data = $this->resData($request);
        // 加入群组
        Gateway::joinGroup($clientId, $roomId);
        // 广播消息
        Gateway::sendToGroup($roomId, json_encode($data));
        // 返回json信息
        return self::showResCode('successful', $data);
    }

    /**
     * 返回信息
     * @param Request $request
     * @return array
     */
    public function resData(Request $request): array
    {
        $data = $request->post('data');
        if (!empty($data['content'])) {
            $data['content'] = $this->repCon($data['content']);
        }
        return [
            'client_id' => $request->post('client_id'),
            'from_nick' => $request->post('from_nick'),
            'from_pic' => $request->post('from_pic'),
            'data' => $data,
            'time' => time()
        ];
    }

    /**
     * 内容获取器 敏感词屏蔽替换
     * @param $value
     * @return string|string[]
     */
    public function repCon($value)
    {
        $str = htmlspecialchars($value);
        $words = file_get_contents(root_path() . 'public/static/words.txt');
        $comment_key = preg_split('/[\r\n]+/', trim($words, "\r\n"));
        return str_replace($comment_key, '***', $str);
    }
}
