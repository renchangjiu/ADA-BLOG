<?php

namespace App\Http\Models;


class Comment {
    public $id;
    public $ip;
    public $artId;
    public $name;
    public $content;
    public $time;
    public $floor;

    // 扩展
    public $artTitle;   // 关联文章表标题

}