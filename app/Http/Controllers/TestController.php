<?php

namespace App\Http\Controllers;

use App\Http\Dao\ArticleDao;
use App\Http\service\CommentService;
use Illuminate\Http\Request;

date_default_timezone_set("Asia/Shanghai");

class TestController extends Controller {

    public function test(Request $request) {
        echo $_SERVER["DOCUMENT_ROOT"];
    }


}
