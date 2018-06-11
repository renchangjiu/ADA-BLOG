<?php

namespace App\Http\utils;



class RedisUtil {


    private static $ip = "192.168.1.20";
    private static $port = 6379;

    public static function getConnection() {
        $conn = new Redis();
        $conn->connect(RedisUtil::$ip, RedisUtil::$port);
        return $conn;
    }
}

// 测试
$conn = RedisUtil::getConnection();
$res = $conn->get("phpCount");
echo $res;