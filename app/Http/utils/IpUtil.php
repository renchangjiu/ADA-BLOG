<?php

namespace App\Http\utils;

class IpUtil {


    public static function getIp(): string {
        $userIp = "";

        /*$request->setTrustedProxies(array('10.32.0.1/16'));
        $userIp = $request->getClientIp();*/

        $userIp = "";
        if (empty($_SERVER["HTTP_VIA"])) {
            $userIp = $_SERVER["REMOTE_ADDR"];
        } else {    // 如果使用了代理服务器
            $userIp = $_SERVER["HTTP_X_FORWARDED_FOR "];
        }

        return $userIp;

    }


}