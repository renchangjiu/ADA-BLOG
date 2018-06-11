<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Exception\MyException;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Models\Letter;
use App\Http\Models\Result;
use App\Http\service\LetterService;
use App\Http\utils\ORMUtil;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LetterController extends Controller {

    public function show(Request $request, $id, LetterService $service) {
        try {
            $letter = $service->show($id);
            return response()->json(Result::success($letter));
        } catch (MyException $e) {
            return response()->json(Result::failed(null, $e->getData()));
        }
    }

    public function list(Request $request, $page, LetterService $service) {
        try {
            $letters = $service->list($page);
            return response()->json(Result::success($letters));
        } catch (MyException $e) {
            return response()->json(Result::failed(null, $e->getData()));
        }
    }


    // 发信
    public function send(Request $request, LetterService $service) {
       $formLetter = ORMUtil::input2Obj(Letter::class, $request->all());
        // 什么都不写值为 null, 全是空白字符也是 null
        if ($formLetter->message != null) {
            try {
                // 私信成功
                $service->insert($formLetter);
                return response()->json(Result::success(null, "私信成功, 博主会尽快查看ヘ(_ _ヘ)"));
            } catch (MyException $e) {
                return response()->json(Result::failed($formLetter, "系统异常, 请检查输入, 重新提交"));
            }
        } else {
            return response()->json(Result::failed("请填写message"));
        }


    }

    // 删除信件
    public function delete(Request $request, $id, LetterService $service) {
        try {
            $service->delete($id);
            return response()->json(Result::success(null, "success"));
        } catch (MyException $e) {
            return response()->json(Result::failed(null, $e->getData()));
        }
    }




}
