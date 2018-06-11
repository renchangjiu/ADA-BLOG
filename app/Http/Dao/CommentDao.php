<?php

namespace App\Http\Dao;


use App\Http\Models\Comment;
use App\Http\utils\MyDB;
use App\Http\utils\ORMUtil;

class CommentDao implements Dao{
    // 返回某文章下总评论数
    public function getTotalCountByArticleId($articleId) {
        $sql = "select count(id) as count from t_comment where artId = ?";
        $res = MyDB::select($sql, [$articleId]);
        return $res[0]->count;
    }

    // 返回总评论数
    public function getTotalCount() {
        $sql = "select count(id) as count from t_comment";
        $res = MyDB::select($sql);
        return $res[0]->count;
    }

    public function findListByArticleId($articleId, $index, $length) {
        $sql = "SELECT *  FROM t_comment  where artId = ?  ORDER BY time DESC limit ?, ?";
        $res = MyDB::select($sql, [$articleId, $index, $length]);

        return $articles = ORMUtil::db2list(Comment::class, $res);
    }

    public function list($index, $length) {
        $sql = "SELECT c.*, a.title as artTitle FROM t_comment as c, t_article as a where c.artId = a.id ORDER BY time DESC limit ?, ?";
        $res = MyDB::select($sql, [$index, $length]);

        return $articles = ORMUtil::db2list(Comment::class, $res);
    }


    public function insert($comment) {
        $sql = "insert into t_comment VALUES (null, ?,  ?, ?, ?, ?, now())";

        return MyDB::insert($sql, [$comment->ip, $comment->artId, htmlspecialchars($comment->content), $comment->floor, htmlspecialchars($comment->name)]);
    }

    // 查询当前文章的最大楼层
    public function findMaxFloorByArtId($artId) {
        $sql = "select max(floor) as maxFloor from t_comment where artId  = ?";
        $res = MyDB::select($sql, [$artId])[0]->maxFloor;
        return $res != null ? $res + 1 : 1;
    }

    public function delete($id) {
        $sql = "delete from t_comment where id = ?";
        return MyDB::delete($sql, [$id]);
    }


    public function deleteByArticleId($artId) {
        $sql = "delete from t_comment where artId = ?";
        return MyDB::delete($sql, [$artId]);
    }

    // 查询当前ip上一次提交评论的时间
    public function findLastInsertTimeByIp($ip) {
        $sql = "select time from t_comment where ip = ? order by time desc limit 0, 1";
        return MyDB::select($sql, [$ip]);

    }


    public function show($id) {
        // TODO: Implement show() method.
    }

    public function update($obj) {
        // TODO: Implement update() method.
    }
}