<?php

namespace App\Http\utils;

use Illuminate\Support\Facades\DB;

/**
 * The actual implementation is still dependent on. Illuminate\Support\Facades\DB,
 * Just add method tips for the ide.
 * Class MyDB
 * @package App\Http\utils
 */
class MyDB {

    /**
     * @param $sql : sql statement
     * @param $params : list
     * @return mixed
     */
    public static function insert($sql, $params = []) {
        return DB::insert($sql, $params);
    }

    /**
     * @param $sql : sql statement
     * @param $params : list
     * @return mixed
     */
    public static function delete($sql, $params = []) {
        return DB::delete($sql, $params);
    }

    /**
     * @param $sql : sql statement
     * @param $params : list
     * @return mixed
     */
    public static function update($sql, $params = []) {
        return DB::update($sql, $params);
    }

    /**
     * @param $sql : sql statement
     * @param $params : list
     * @return mixed
     */
    public static function select($sql, $params = []) {
        return DB::select($sql, $params);
    }

    /**
     * 执行其他的语句
     * @param $sql
     */
    public static function statement($sql) {
        DB::statement($sql);
    }


    /**
     * 开启事务
     */
    public static function beginTransaction() {
        DB::beginTransaction();
    }

    /**
     * 回滚事务
     */
    public static function rollback() {
        DB::rollback();
    }

    /**
     * 提交事务
     */
    public static function commit() {
        DB::commit();
    }


}