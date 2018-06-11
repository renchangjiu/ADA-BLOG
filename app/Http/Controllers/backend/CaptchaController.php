<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Models\Result;
use App\Http\utils\Captcha;
use App\Http\utils\MyDB;
use App\Http\utils\UUID;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CaptchaController extends Controller {


    public function captcha(Request $request) {

        $stdClass = Captcha::getCaptcha();
        $str = $stdClass->str;      // 验证码图片答案

        // 存入key: uuid, value: 验证码图片答案
        $captchaId = UUID::uuid();
        Redis::set("captchaId:$captchaId", $str);
        Redis::expire("captchaId:$captchaId", 60);


        // 返回id 及base64码
        return response()->json(Result::success(["imgBase64" => $stdClass->imgBase64, "captchaId" => $captchaId]));
    }



}
