<?php

use Illuminate\Support\Facades\Route;


// 测试用
Route::any("/test", "TestController@test");


/*
 * 路由
 */

Route::middleware(["mobile", "html"])->group(function () {
    // 前台首页
    // 1/0;
    Route::view("/", "index");
});

Route::middleware(["html"])->group(function () {
    // 前台首页
    // Route::view("/", "index");

    // 移动端首页
    Route::view("/m", "mobile/index");

    // 关于
    Route::view("/about", "about");

    // 文章
    Route::view("/article/{id}", "article")->where("id", "[0-9]+");

    // 联系
    Route::view("/contact", "contact");

    // 登录
    Route::view("/admin/sign-in", "back-end/sign-in");

});


// 按标签搜索
Route::get("/tag/{tag}", "ArticleController@search");


// 后台相关
Route::group(["middleware" => ["html"], "prefix" => "admin"], function () {

    // 首页
    Route::view("/", "back-end/index");

    // 写文章
    Route::view("/article/write", "back-end/write-article");

    // 修改文章
    Route::view("/article/update/{id}", "back-end/update-article")->where("id", "[0-9]+");

    // 后台信件管理页
    Route::view("/letter", "back-end/letter");

    // 展示信件全部内容
    Route::view("/letter/{id}", "back-end/letter-detail");

    // 阅读记录
    Route::view("/read-log", "back-end/read-log");

    // 登录日志
    Route::view("/sign-in-log", "back-end/sign-in-log");

    // 评论
    Route::view("/comment", "back-end/comment");

    // 标签
    Route::view("/tag", "back-end/tag");
});


// todo 草稿箱
// todo 优化图片上传