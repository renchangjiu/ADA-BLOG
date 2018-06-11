<?php

namespace App\Http\service;


interface Service {
    // public function list(...$args);
    // public function insert(...$args);
    public function delete($id);
    // public function update($obj);
    public function show($id);
}