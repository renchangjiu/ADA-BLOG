<?php

namespace App\Http\Controllers\backend;


use App\Http\Controllers\Controller;
use App\Http\Exception\MyException;
use App\Http\Models\Result;
use App\Http\service\ReadLogService;

class ReadLogController extends Controller {

    public function list($page, ReadLogService $service) {
        try {
            $letters = $service->list($page);
            return response()->json(Result::success($letters));
        } catch (MyException $e) {
            return response()->json(Result::failed(null, $e->getData()));
        }
    }


}