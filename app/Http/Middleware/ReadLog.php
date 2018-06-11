<?php

namespace App\Http\Middleware;

use App\Http\utils\IpUtil;
use App\Http\utils\MyDB;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ReadLog {

    public function handle($request, Closure $next) {
        // 获取用户ip
        $userIp = IpUtil::getIp();

        // 获取路由参数, 即文章id
        $artId = $request->route("id");

        // 先查询数据库中有无该ip访问该文章的记录
        $sql = "select * from t_read_log WHERE artId = ? AND ip = ?";
        $res = MyDB::select($sql, [$artId, $userIp]);

        if (empty($res)) {  // 若无记录, 则添加记录, 同时t_article : readNum + 1
            try {
                // 事务
                MyDB::beginTransaction();
                $sql = "insert into t_read_log VALUES (null, ?, ?, now())";
                // try
                MyDB::insert($sql, [$artId, $userIp]);

                $sql = "update t_article set readNum = readNum + 1 WHERE id = ?";
                MyDB::update($sql, [$artId]);
                MyDB::commit();
            } catch (QueryException  $e) {
                MyDB::rollback();
                Log::warning("su-添加访问日志错误, 请检查readLog;");
            }
        }

        return $next($request);
    }



}
