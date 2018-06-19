<?php

namespace App\Http\Dao;


use App\Http\Exception\MyException;
use App\Http\Models\Article;
use App\Http\utils\MyDB;
use App\Http\utils\ORMUtil;
use Monolog\Formatter\FormatterInterface;
use Tests\Unit\ExampleTest;

class ArticleDao implements Dao {

    public function show($id) {
        $sql = "SELECT * FROM t_article WHERE id = ?";
        return MyDB::select($sql, [$id]);
    }


    public function getTotalCount() {
        $sql = "select count(id) as count from t_article";
        $res = MyDB::select($sql);
        return $res[0]->count;
    }

    public function list($index, $length) {
        $sql = "SELECT id, title, summary, tags, readNum, editTime FROM t_article ORDER BY editTime DESC limit ?, ?";
        $res = MyDB::select($sql, [$index, $length]);

        return $articles = ORMUtil::db2list(Article::class, $res);
    }

    public function delete($id) {
        $sql = "delete from t_article where id = ?";
        return MyDB::delete($sql, [$id]);
    }

    public function insert($a) {
        $sql = "insert into t_article VALUES (null, ?, ?, ?, ?, now(), 0)";
        $params = [$a->title, $a->summary, $a->content, $a->tags];
        return MyDB::insert($sql, $params);
    }

    public function update($a) {
        $sql = "update t_article set title = ?, summary = ?, content = ?, tags = ?, editTime = now() where id = ?";
        $params = [$a->title, $a->summary, $a->content, $a->tags, $a->id];
        return MyDB::update($sql, $params);
    }

    public function search($input) {
        $sql = "select id, title from t_article where title like '%$input%' or summary like '%$input%' or content like '%$input%'";

        return MyDB::select($sql);
    }

    public function findArticlesByTag($tagId) {
        $list = $this->findTags();
        $res = [];
        for ($i = 0; $i < count($list); $i++) {
            $tags = $list[$i]->tags;
            for ($j = 0; $j < count($tags); $j++) {
                if ($tagId == $tags[$j]) {
                    $res[] = $list[$i];
                }
            }
        }
        return $res;
    }


    /** 查询所有文章的标签列表
     * @return array
     */
    private function findTags() {
        $sql = "select id, title, tags from t_article";
        $res = MyDB::select($sql);
        $list = ORMUtil::db2list(Article::class, $res);
        for ($i = 0; $i < count($list); $i++) {
            $list[$i]->tags = explode("|", $list[$i]->tags);
        }
        return $list;
    }




}
