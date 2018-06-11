<?php

namespace App\Http\Dao;


use App\Http\Models\Letter;
use App\Http\utils\MyDB;
use App\Http\utils\ORMUtil;

class LetterDao implements Dao {

    public function getTotalCount() {
        $sql = "select count(id) as count from t_letter";
        $res = MyDB::select($sql);
        return $res[0]->count;
    }

    public function list($index, $length) {
        $sql = "SELECT * FROM t_letter ORDER BY time DESC limit ?, ?";
        $res = MyDB::select($sql, [$index, $length]);

        return $articles = ORMUtil::db2list(Letter::class, $res);
    }

    public function show($id) {
        $sql = "select * from t_letter where id = ?";
        $res = MyDB::select($sql, [$id]);

        $updateSql = "update t_letter set isRead = 1 where id = ?";
        MyDB::update($updateSql, [$id]);

        return $res;
    }

    public function delete($id) {
        $sql = "delete from t_letter where id = ?";
        return MyDB::delete($sql, [$id]);
    }

    public function insert($letter) {
        $sql = "insert into t_letter VALUES (null, ?, ?, ?, now(), 0)";
        return MyDB::insert($sql, [htmlspecialchars($letter->name), htmlspecialchars($letter->email), htmlspecialchars($letter->message)]);
    }

    public function update($letter) {
        // TODO: Implement update() method.
    }



}