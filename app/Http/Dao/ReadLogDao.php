<?php

namespace App\Http\Dao;


use App\Http\Models\Article;
use App\Http\utils\MyDB;
use App\Http\utils\ORMUtil;
use Tests\Unit\ExampleTest;

class ReadLogDao implements Dao {

    public function getTotalCount() {
        $sql = "select count(id) as count from t_read_log";
        $res = MyDB::select($sql);
        return $res[0]->count;
    }

    public function list($index, $length) {
        $sql = "select l.id, l.ip, l.time, l.artId, art.title as artTitle from t_read_log l, t_article art where l.artId = art.id ORDER BY time DESC limit ?, ?";
        return MyDB::select($sql, [$index, $length]);
    }

    public function deleteByArticleId($artId) {
        $sql = "delete from t_read_log WHERE artId = ?";
        return MyDB::delete($sql, [$artId]);
    }

    public function update($readLog) {
        // TODO: Implement update() method.
    }

    public function show($id) {
        // TODO: Implement show() method.
    }

    public function insert($obj) {
        // TODO: Implement insert() method.
    }

    public function delete($id) {
        // TODO: Implement delete() method.
    }
}