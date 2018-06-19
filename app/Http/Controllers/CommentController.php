<?php

namespace App\Http\Controllers;

use App\Http\Exception\MyException;
use App\Http\Models\Result;
use App\Http\service\CommentService;


class CommentController extends Controller {

    public function findListByArticleId($articleId, $page, CommentService $service) {
        try {
            $list = $service->findListByArticleId($articleId, $page);
            return response()->json(Result::success($list));
        } catch (MyException $e) {
            return response()->json(Result::success(null, $e->getData()));
        }
    }




}
