<?php
namespace App\Http\Models;


class Article {

    public $id;
    public $summary;
    public $title;
    public $content;
    public $tags;
    public $editTime;
    public $readNum;


    public function __toString() {
        return "id: $this->id, title: $this->title";
    }



}