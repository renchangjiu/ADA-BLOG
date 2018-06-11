<?php

namespace App\Http\Dao;


use App\Http\utils\MyDB;

class SignInLogDao implements Dao {

    public function getTotalCount() {
        $sql = "select count(id) as count from t_sign_in_log";
        $res = MyDB::select($sql);
        return $res[0]->count;
    }

    public function list($index, $length) {
        $sql = "select * from t_sign_in_log ORDER BY time DESC limit ?, ?";
        return MyDB::select($sql, [$index, $length]);
    }


    public function update($signInLog) {
        // TODO: Implement update() method.
    }

    public function show($id) {
        // TODO: Implement show() method.
    }

    public function delete($id) {
        // TODO: Implement delete() method.
    }

    public function insert($obj) {
        // TODO: Implement insert() method.
    }
}