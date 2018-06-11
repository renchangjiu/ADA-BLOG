<?php

namespace App\Http\Dao;


use App\Http\Models\Tag;
use App\Http\utils\MyDB;
use App\Http\utils\ORMUtil;
use Tests\Unit\ExampleTest;

class TagDao implements Dao {

    public function getTotalCount() {
        $sql = "select count(id) as count from t_tag";
        $res = MyDB::select($sql);
        return $res[0]->count;
    }

    public function list() {
        $sql = "SELECT * FROM t_tag ORDER BY id DESC";
        $res = MyDB::select($sql);

        return $list = ORMUtil::db2list(Tag::class, $res);
    }

    public function insert($tag) {
        $sql = "insert into t_tag values (null, ?)";
        return MyDB::insert($sql, [$tag->name]);
    }


    public function delete($id) {
        $sql = "delete from t_tag where id = ?";
        return MyDB::delete($sql, [$id]);
    }



    public function update($tag) {
        // TODO: Implement update() method.
    }

    public function show($id) {
        // TODO: Implement show() method.
    }


}