<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Exception\MyException;
use App\Http\Models\Result;
use App\Http\Models\Tag;
use App\Http\service\ArticleService;
use App\Http\service\TagService;
use App\Http\utils\ORMUtil;
use Illuminate\Http\Request;

class TagController extends Controller {

    public function list(TagService $service) {
        $tags = $service->list();
        return response()->json(Result::success($tags, "success"));
    }


    public function insert(Request $request, TagService $service) {
        $tag = ORMUtil::input2Obj(Tag::class, $request->all());
        if (mb_strlen($tag->name, "utf-8") < 2) {
            return response()->json(Result::failed(null, "The name should not be less than two characters."));
        } else {
            try {
                $tag->name = htmlspecialchars($tag->name);
                $service->insert($tag);
                return response()->json(Result::success(null, "success"));
            } catch (MyException $e) {
                return response()->json(Result::failed(null, $e->getData()));
            }

        }
    }

    public function delete($id, TagService $service, ArticleService $as) {
        try {
            $as->findArticlesByTag($id);    //
            return response()->json(Result::failed(null, "目标标签下还有文章, 不可被删除"));
        } catch (MyException $e) {
            $service->delete($id);
            return response()->json(Result::success());

        }

    }


}
