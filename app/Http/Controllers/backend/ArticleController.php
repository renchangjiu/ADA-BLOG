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
    public function uploadImage(Request $request) {

        $extension = strrchr($_FILES["file"]["name"], ".");     // .jpg
        $fileName = uniqid() . $extension;    // 新的文件名
        $path = getenv("ARTICLE_IMAGE");      // 保存图片的路径

        // 被允许的文件扩展名
        $permitExtension = array(".jpg", ".JPG", ".png", ".PNG", "bmp",);
        if (!in_array($extension, $permitExtension)) {
            return response()->json(UploadImageResult::failed("filetype", "不支持的文件扩展名"));
        }

        // // 被允许的文件最大字节数
        $maxSize = 1024 * 1024 * 2;
        if ($_FILES["file"]["size"] > $maxSize) {
            return response()->json(UploadImageResult::failed(true, "upload image size must <= 2mb"));
        }

        $date = date("Ymd");
        $image_path = $path . $date;
        if (!is_dir($image_path)) {
            mkdir($image_path);
        }

        $file = "$image_path/$fileName";

        $url = "/article/image/$date/$fileName";      // 返回给前端的图片的URL

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
            return response()->json(UploadImageResult::success($url));
        } else {
            return response()->json(UploadImageResult::failed(true, $file));
        }
    }
}


class UploadImageResult {
    public $error;
    public $path;
    public $msg;

    /**
     * UploadImageResult constructor.
     * @param $error
     * @param $path
     * @param $msg
     */
    private function __construct($error, $path, $msg) {
        $this->error = $error;
        $this->path = $path;
        $this->msg = $msg;
    }

    public static function success($path) {
        return new UploadImageResult(false, $path, null);
    }

    public static function failed($error, $msg) {
        return new UploadImageResult($error, null, $msg);
    }


}
