<?php

namespace App\Http\Dao;


use App\Http\utils\MyDB;

class AuthDao implements Dao {

    public function findAdminByName($name) {
        $sql = "select name from t_admin where name = ?";
        return MyDB::select($sql, [$name]);
    }


    public function findAdminByNameAndPassword($name, $password) {
        $signIn = "select * from t_admin where name = ? and password = ?";
        return MyDB::select($signIn, [$name, $password]);
    }


    public function insert($obj) {
        // TODO: Implement insert() method.
    }

    public function delete($id) {
        // TODO: Implement delete() method.
    }

    public function update($obj) {
        // TODO: Implement update() method.
    }

    public function show($id) {
        // TODO: Implement show() method.
    }
}