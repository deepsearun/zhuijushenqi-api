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

Route::group('api/:version', function () {
    // 获取影片轮播图
    Route::get('vod/slider', ':version.VodController/slider');
    // 今日更新影片
    Route::get('vod/today',':version.VodController/todayUp');
    // 最新资讯
    Route::get('art/new',':version.ArtController/new');
    // 获取资讯轮播图
    Route::get('art/slider',':version.ArtController/slider');
    // 热门电影
    Route::get('vod/hot',':version.VodController/getHotMovie');
});
