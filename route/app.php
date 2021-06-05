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

Route::any('api/:version/config', function () {
    return json([
        // 防封模式 开启后关闭视频播放功能 点击影视将直接分享。
        'check' => true,
        // 首页标题显示
        'index_name' => '热门影视分享',
        // 滚动公告内容
        'notices' => false, //[ '免责声明：小程序内容来源于网络，服务器未存储任何视频，如有侵犯您的权益请告知我们尽快处理']
        // 轮播图 3D效果
        'effect3d' => false,
        // 搜索框内容
        'searchInput' => '输入影片名 演员或导演搜索'
    ]);
});

Route::any('api/:version/newconfig', function () {
    return json(config('api.viewsConfig'));
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
    // 搜索热词
    Route::get('vod/search/hotwords', ':version.VodController/searchHotWords');
    // 影片详情
    Route::get('vod/detail/:vod_id', ':version.VodController/detail');
})->middleware(\app\middleware\SecurityCheck::class);
