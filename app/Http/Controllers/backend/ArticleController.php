<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Exception\MyException;
use App\Http\Models\Article;
use App\Http\Models\Result;
use App\Http\service\ArticleService;
use App\Http\utils\ORMUtil;
use http\Env\Response;
use Illuminate\Http\Request;
use stdClass;

class ArticleController extends Controller {

    // 发布文章
    public function publish(Request $request, ArticleService $service) {
        $a = ORMUtil::input2Obj(Article::class, $request->all());
        try {
            if (empty($a->title) || empty($a->summary) || empty($a->content) || empty($a->tags)) {
                return response()->json(Result::failed(null, "缺少必要字段"));
            }
            $service->insert($a);
        } catch (MyException $e) {
            return response()->json($e->getData());
        }
        return response()->json(Result::success(null, "success"));
    }

    // 修改文章
    public function update(Request $request, ArticleService $service) {
        $a = ORMUtil::input2Obj("App\Http\Models\Article", $request->all());
        // return response()->json($a);
        // exit();
        try {
            if (empty($a->id) || empty($a->title) || empty($a->summary) || empty($a->content) || empty($a->tags)) {
                return response()->json(Result::failed(null, "缺少必要字段"));
            }
            $service->update($a);
        } catch (MyException $e) {
            return response()->json($e->getData());
        }
        return response()->json(Result::success(null, "success"));
    }


    public function delete(Request $request, $id, ArticleService $service) {
        try {
            $service->delete($id);
            return response()->json(Result::success());
        } catch (MyException $e) {
            return response()->json(Result::failed(null, "删除失败"));
        }
    }


    // 图片上传
    // !!! 这里的代码轻易不要修改, 会出现一些莫名其妙的问题
    public function uploadImage(Request $request) {


        $extension = strrchr($_FILES["file"]["name"], ".");     // .jpg
        $fileName = uniqid() . $extension;    // 新的文件名
        $path = $_SERVER["DOCUMENT_ROOT"] . "storage/images/";      // 保存图片的路径
        $file = $path . $fileName;

        // 被允许的文件扩展名
        $permitExtension = array(".jpg", ".png", "bmp",);
        if (!in_array($extension, $permitExtension)) {
            echo "不支持的文件扩展名", "\n";
            return '{"errors":true, msg:不支持的文件扩展名}';
        }

        $url = "/storage/images/$fileName";
        // // 被允许的文件最大字节数
        // $maxSize = 1024 * 1024 * 2;
        // if ($_FILES["file"]["size"] > $maxSize) {
        //     echo "上传文件大小超过限制, 请保持在 ".$maxSize." 字节以下", "\n";
        //     return false;
        // }
        // var_dump($_FILES);

        $result = new stdClass();
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
            $result->error = false;
            $result->path = $url;
            return response()->json($result);
        } else {
            $result->error = true;
            $result->msg = $file;
            return response()->json($result);
        }


    }


}
