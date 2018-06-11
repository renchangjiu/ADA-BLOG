<?php

use Illuminate\Support\Facades\Route;

// 测试用
Route::any("/test", "TestController@test");

// 显示一篇文章
Route::middleware(["readLog"])->group(function () {
    Route::any("/article/{id}", "ArticleController@show")->where("id", "[0-9]+");
});

// 登录
Route::middleware(["signInLog"])->group(function () {
    Route::any("/admin/sign-in", "backend\AuthController@signIn");
});

// 分页显示文章列表
Route::any("/articles/{page}", "ArticleController@list")->where("page", "[0-9]+");

// 以当前id 请求上及下一篇文章
Route::any("/article/pre/next/{id}", "ArticleController@preOrNextArticle")->where("id", "[0-9]+");

// 私信
Route::post("/letter/send", "backend\LetterController@send");

// 提交评论
Route::post("/comment/send", "backend\CommentController@send");

// 获得验证码
Route::any("/captcha", "backend\CaptchaController@captcha");

// 登出
Route::get("/admin/sign-out", "backend\AuthController@signOut");

// 按文字搜索
Route::any("/search", "ArticleController@search");

// 评论 : 获取某文章下所有评论
Route::any("/comments/{articleId}/{page}", "CommentController@findListByArticleId")->where(["articleId" => "[0-9]+", "page" => "[0-9]+"]);

// 文章 : 返回某标签下所有文章
Route::any("/articles/tag/{tagId}", "ArticleController@findArticlesByTag")->where("tagId", "[0-9]+");


// 返回标签列表
Route::get("/tags", "backend\TagController@list");


// 后台
Route::group(["middleware" => ["admin"], "prefix" => "admin"], function () {
    /*
     * 文章
     */
    // 分页显示文章列表
    Route::any("/articles/{page}", "ArticleController@list")->where("page", "[0-9]+");

    // 删除
    Route::any("/article/delete/{id}", "backend\ArticleController@delete")->where("id", "[0-9]+");

    // 上传图片
    Route::any("/article/write/image", "backend\ArticleController@uploadImage");

    // 发布
    Route::any("/article/publish", "backend\ArticleController@publish");

    // 修改
    Route::any("/article/update", "backend\ArticleController@update");


    /**
     * 信件
     */
    // 获取所有信件
    Route::any("/letters/{page}", "backend\LetterController@list")->where("page", "[0-9]+");

    // 展示信件全部内容
    Route::any("/letter/{id}", "backend\LetterController@show")->where("id", "[0-9]+");

    // 删除
    Route::any("/letter/delete/{id}", "backend\LetterController@delete")->where("id", "[0-9]+");


    /**
     * 阅读记录
     */
    // 列表
    Route::any("/read-logs/{page}", "backend\ReadLogController@list")->where("page", "[0-9]+");

    /**
     * 登录记录
     */
    // 列表
    Route::any("/sign-in-logs/{page}", "backend\SignInLogController@list")->where("page", "[0-9]+");


    /*
     * 评论
     */
    // 评论 : 获取所有评论
    Route::any("/comments/{page}", "backend\CommentController@list")->where(["page", "[0-9]+"]);

    // 评论 : 删除
    Route::any("/comment/delete/{id}", "backend\CommentController@delete")->where("id", "[0-9]+");

    /**
     * 标签
     */
    // 列表
    Route::any("/tags/{page}", "backend\TagController@list")->where("page", "[0-9]+");

    // 删除
    Route::any("/tag/delete/{id}", "backend\TagController@delete")->where("id", "[0-9]+");

    // 插入
    Route::any("/tag/insert", "backend\TagController@insert");

});

