<?php

namespace App\Http\Controllers\backend;


use App\Http\Controllers\Controller;
use App\Http\Exception\MyException;
use App\Http\Models\Result;
use App\Http\service\SignInLogService;

class SignInLogController extends Controller {

    public function list($page, SignInLogService $service) {
        try {
            $list = $service->list($page);
            return response()->json(Result::success($list));
        } catch (MyException $e) {
            return response()->json(Result::failed(null, $e->getData()));
        }

    }


}