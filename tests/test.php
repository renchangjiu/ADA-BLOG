<?php

class Article {
    public $id;
    public $summary;
    public $title;
    public $content;
    public $tags;
    public $editTime;
    public $readNum;
}

$a = new Article();
$a->id = "<>";
$a->content = "ddd";

foreach ($a as $key => &$value) {
    // echo $key;
    $value = $value . "q";
}
var_dump($a);



