<?php

namespace App\Http\Middleware;

use App\Http\utils\IpUtil;
use App\Http\utils\MyDB;
use Closure;

class SignInLog {
    public function handle($request, Closure $next) {
        // id, ip, time
        $sql = "insert into t_sign_in_log values (null, ?, now())";
        $ip = IpUtil::getIp();
        $res = MyDB::insert($sql, [$ip]);



        return $next($request);
    }


}


