<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

/**
 * 蓝光资源站
 */
Route::get('api/:version/lg', function () {
    $url = input('url');
    $arr = file_get_contents('https://j.languang.wfss100.com/json/json.php?url=' . $url);
    $arr = json_decode($arr, true);
    if (!empty($arr['url'])) {
        return redirect($arr['url']);
    }
    return json(['msg' => '解析错误'], 404);
});

/**
 * 线上小程序配置
 */
Route::any('api/:version/config', function () {
    return json([
        // 防封模式 开启后关闭视频播放功能 点击影视将直接分享。
        'check' => true,
        // 首页tabs
        'tabs' => [
            ['id' => 0, 'name' => '推荐'],
            ['id' => 1, 'name' => '电影'],
            ['id' => 2, 'name' => '电视剧'],
            ['id' => 3, 'name' => '综艺'],
            ['id' => 4, 'name' => '动漫'],
        ],
        // 蓝光解析
        'renrenmi' => 'https://ku.renrenmi.cc/api/?type=app&key=ZuQJy69yvWthoB15rq&url=',
        // 蓝光解析2
        'lg' => 'https://sp.2oc.cc/api/v1/lg?url=',
        // 官方直链解析
        'renrenmi2' => 'https://vip.renrenmi.cc/api/?type=app&key=hivHDOefp7HaUibqfQ&url=',
        // 首页banner广告
        'index_banner' => 'adunit-41c31fffb1378f4c',
        // 首页banner广告2
        'index_banner2' => 'adunit-02537f9dc9a36baa',
        // 首页banner广告3
        'index_banner3' => 'adunit-1300892bea148fef',
        // 搜索页面banner广告
        'search_banner' => 'adunit-357587f6b97ad288',
        // 视频前贴广告
        'play_start_ad' => 'adunit-8a7a6f4e595d1fea', //adunit-8a7a6f4e595d1fea
        // 视频广告
        'video_ad' => 'adunit-b597668eb3fbed1a',
        // 激励广告
        'rewarded_ad' => 'adunit-c8a413b638657b5e', // adunit-c8a413b638657b5e
        // 插屏广告
        'interAd' => 'adunit-63915039ea3bd3ab'
    ]);
});

/**
 * 线上小程序配置
 */
Route::any('api/:version/config2', function () {
    return json([
        // 防封模式 开启后关闭视频播放功能 点击影视将直接分享。
        'check' => false,
        // 首页tabs
        'tabs' => [
            ['id' => 0, 'name' => '推荐'],
            ['id' => 1, 'name' => '电影'],
            ['id' => 2, 'name' => '电视剧'],
            ['id' => 3, 'name' => '综艺'],
            ['id' => 4, 'name' => '动漫'],
        ],
        // 蓝光解析
        'renrenmi' => 'https://ku.renrenmi.cc/api/?type=app&key=ZuQJy69yvWthoB15rq&url=',
        // 蓝光解析2
        'lg' => 'https://sp.2oc.cc/api/v1/lg?url=',
        // 官方直链解析
        'renrenmi2' => 'https://vip.renrenmi.cc/api/?type=app&key=hivHDOefp7HaUibqfQ&url=',
        // 首页banner广告
        'index_banner' => 'adunit-41c31fffb1378f4c',
        // 首页banner广告2
        'index_banner2' => 'adunit-02537f9dc9a36baa',
        // 首页banner广告3
        'index_banner3' => 'adunit-1300892bea148fef',
        // 搜索页面banner广告
        'search_banner' => 'adunit-357587f6b97ad288',
        // 视频前贴广告
        'play_start_ad' => 'adunit-8a7a6f4e595d1fea', //adunit-8a7a6f4e595d1fea
        // 视频广告
        'video_ad' => 'adunit-b597668eb3fbed1a',
        // 激励广告
        'rewarded_ad' => 'adunit-c8a413b638657b5e', // adunit-c8a413b638657b5e
        // 插屏广告
        'interAd' => 'adunit-63915039ea3bd3ab'
    ]);
});

Route::group('api/:version', function () {
    // 获取影片轮播图
    Route::get('vod/slider', ':version.VodController/slider');
    // 今日更新影片
    Route::get('vod/today', ':version.VodController/todayUp');
    // 最新资讯
    Route::get('art/new', ':version.ArtController/new');
    // 获取资讯轮播图
    Route::get('art/slider', ':version.ArtController/slider');
    // 热门电影
    Route::get('vod/hot/movie', ':version.VodController/getHotMovie');
    // 热门电视剧
    Route::get('vod/hot/tv', ':version.VodController/getHotTv');
    // 热门综艺
    Route::get('vod/hot/variety', ':version.VodController/getHotVariety');
    // 热门动漫
    Route::get('vod/hot/comic', ':version.VodController/getHotComic');
    // 热门影视列表
    Route::get('vod/hot/index', ':version.VodController/getHotList');
    // 搜索影片
    Route::get('vod/search/index', ':version.VodController/search');
    // 搜索关键词提示
    Route::get('vod/search/complete', ':version.VodController/searchComplete');
    // 搜索热词
    Route::get('vod/search/hotwords', ':version.VodController/searchHotWords');
    // 影片详情
    Route::get('vod/detail/:vod_id', ':version.VodController/detail');
});

/**
 * 加入房间
 */
Route::post('api/:version/room/add', ':version.ChatServer/bind');

/**
 * 发送消息
 */
Route::post('api/:version/room/send', ':version.ChatServer/send');


/**
 * 获取消息记录
 */
Route::get('api/:version/room/index', ':version.ChatServer/index');

/**
 * 获取弹幕
 */
Route::get('api/:version/room/dm', ':version.ChatServer/dm');

/**
 * 获取在线人数
 */
Route::get('api/:version/room/online', ':version.ChatServer/online');


/**
 * 退出房间
 */
Route::post('api/:version/room/exit', ':version.ChatServer/exit');

/**
 * 短链接生成
 */
Route::get('api/:version/suo', function () {
    $url = input('url', '');
    $api = 'https://api.btstu.cn/mrw/api.php?url=' . $url;
    $res = \app\common\lib\HttpCurl::url($api)->get()->jsonToArray();
    return json(['url' => $res['shorturl'] ?? '获取下载链接失败']);
});
