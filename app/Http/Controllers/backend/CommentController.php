<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Exception\MyException;
use App\Http\Models\Comment;
use App\Http\Models\Result;
use App\Http\service\CommentService;
use App\Http\utils\IpUtil;
use App\Http\utils\ORMUtil;
use Illuminate\Http\Request;

date_default_timezone_set("Asia/Shanghai");

class CommentController extends Controller {

    // 提交评论
    public function send(Request $request, CommentService $service) {
        $formComment = ORMUtil::input2Obj(Comment::class, $request->all());

        // 什么都不写值为 null, 全是空白字符也是 null
        if ($formComment->content != null) {
            try {
                if ($formComment->name == null) {
                    $formComment->name = "匿名";
                }
                $ip = IpUtil::getIp();
                $formComment->ip = $ip;     // 记录评论人ip

                // 30秒内不允许两次提交评论
                $lastTime = strtotime($service->findLastInsertTimeByIp($ip));
                $now = time();
                $pass = $now - $lastTime;
                if ($pass >= 30) {
                    $service->insert($formComment);
                    return response()->json(Result::success(null, "私信成功, 博主会尽快查看ヘ(_ _ヘ)"));
                } else {
                    return response()->json(Result::failed(null, "请不要在30秒内提交两次评论ヘ(_ _ヘ)"));
                }
            } catch (MyException $e) {
                return response()->json(Result::failed($formComment, "系统异常, 请检查输入, 重新提交"));
            }
        } else {
            return response()->json(Result::failed("请填写message"));
        }
    }


    /*public function show(Request $request, $id, LetterService $service) {
        try {
            $letter = $service->show($id);
            return response()->json(Result::success($letter));
        } catch (MyException $e) {
            return response()->json(Result::failed(null, $e->getData()));
        }
    }*/

    public function list(Request $request, $page, CommentService $service) {

        try {
            $list = $service->list($page);
            return response()->json(Result::success($list));
        } catch (MyException $e) {
            return response()->json(Result::failed(null, $e->getData()));
        }
    }




    // 删除评论
    public function delete(Request $request, $id, CommentService $service) {
        try {
            $service->delete($id);
            return response()->json(Result::success(null, "success"));
        } catch (MyException $e) {
            return response()->json(Result::failed(null, $e->getData()));
        }
    }




}
