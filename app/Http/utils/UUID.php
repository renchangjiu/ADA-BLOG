<?php

namespace App\Http\utils;


class UUID {

    public static function uuid($prefix = "") {    //可以指定前缀
        $str = md5(uniqid(mt_rand(), true));
        // $uuid = substr($str, 0, 8);
        // $uuid .= substr($str, 8, 4);
        // $uuid .= substr($str, 12, 4);
        // $uuid .= substr($str, 16, 4);
        // $uuid .= substr($str, 20, 12);
        return $prefix . $str;
    }
}


// echo UUID::uuid();