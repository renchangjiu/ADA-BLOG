<?php

namespace App\Http\Controllers;

use App\Http\Exception\MyException;
use App\Http\Models\Result;
use App\Http\service\ArticleService;
use Illuminate\Http\Request;


class ArticleController extends Controller {

    public function list($page, ArticleService $service) {
        try {
            $articles = $service->list($page);
            return response()->json(Result::success($articles));
        } catch (MyException $e) {
            return response()->json(Result::failed(null, $e->getData(), 450));
        }
    }


    public function show($id, ArticleService $service) {
        try {
            $article = $service->show($id);
            return response()->json(Result::success($article));
        } catch (MyException $e) {
            return response()->json(Result::failed(null,  $e->getData()));
        }
    }

    public function preOrNextArticle($id, ArticleService $service) {
        $articleList = $service->showPreOrNextArticle($id);

        return response()->json(Result::success($articleList));
    }


    public function search(Request $request, ArticleService $service) {
        $input = $request->input("input");
        $list = $service->search($input);
        return response()->json(Result::success($list));
    }

    // 查询某标签下所有文章
    public function findArticlesByTag($tagId, ArticleService $service) {
        try {
            $list = $service->findArticlesByTag($tagId);
            return response()->json(Result::success($list));
        } catch (MyException $e) {
            return response()->json(Result::failed(null, $e->getData()));
        }

    }



}
