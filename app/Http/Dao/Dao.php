<?php

namespace App\Http\Dao;


interface Dao {
    public function insert($obj);
    public function delete($id);
    public function update($obj);
    public function show($id);
}