<?php

namespace App\Http\Models;


class ReadLog {

    public $id;
    public $artId;
    public $ip;
    public $time;

    // 关联文章表 -- 标题
    public $artTitle;


}