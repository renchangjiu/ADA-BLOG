<?php

namespace App\Http\Models;


class UploadImageResult {
    public $error;      // 是否错误
    public $path;       // 返回给前端的图片的URL
    public $msg;        // 错误信息, 前台不使用

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