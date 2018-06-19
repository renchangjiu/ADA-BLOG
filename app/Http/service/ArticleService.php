<?php

namespace App\Http\service;

use App\Http\Dao\ArticleDao;
use App\Http\Dao\CommentDao;
use App\Http\Dao\ReadLogDao;
use App\Http\Exception\MyException;
use App\Http\Models\Article;
use App\Http\Models\Page;
use App\Http\utils\MyDB;
use App\Http\utils\ORMUtil;

class ArticleService implements Service {

    private $ad;
    private $rld;
    private $cd;

    public function __construct() {
        $this->ad = new ArticleDao();
        $this->rld = new ReadLogDao();
        $this->cd = new CommentDao();
    }


    public function list($curPage) {
        $totalCount = $this->getTotalCount();
        $pageSize = 5;
        $paginator = new Page($curPage, $pageSize, $totalCount);
        if ($curPage > $paginator->getTotalPage()) {
            throw new MyException(0, "The target page number exceeds the maximum page number.");
        }
        $articles = $this->ad->list($paginator->getIndex(), $paginator->getPageSize());
        foreach ($articles as $article) {
            $article->summary = self::subStr($article->summary);
            $article->tags = self::tagToArray($article->tags);
            $article->editTime = date("Y-m-d", strtotime($article->editTime));
        }
        $paginator->setObjects($articles);
        return $paginator;
    }

    public function getTotalCount() {
        return $this->ad->getTotalCount();
    }


    public function show($articleId) {
        $res = $this->ad->show($articleId);
        if (count($res) != 0) {
            $article = ORMUtil::db2one(Article::class, $res);
            $article->tags = self::tagToArray($article->tags);
            $article->editTime = self::dateFormat($article->editTime);
            return $article;
        } else {
            throw new MyException(0, "No query to the article.");
        }

    }


    public function insert(Article $a) {
        $a->title = htmlspecialchars($a->title);
        $a->summary = htmlspecialchars($a->summary);
        $res = $this->ad->insert($a);
        if ($res != 1) {
            throw new MyException(0, "插入文章失败");
        }
    }

    public function update(Article $a) {
        $res = $this->ad->update($a);
        if ($res != 1) {
            throw new MyException(0, "修改失败");
        }

    }

    // 删除文章, 并先删除访问记录及评论(如果有的话)
    public function delete($id) {
        MyDB::beginTransaction();
        $this->rld->deleteByArticleId($id);
        $this->cd->deleteByArticleId($id);
        $artRes = $this->ad->delete($id);
        if ($artRes != 1) {
            MyDB::rollback();
            throw new MyException(0, "删除失败");
        }
        MyDB::commit();
    }

    public function search($input) {
        $res = $this->ad->search($input);
        $list = ORMUtil::db2list(Article::class, $res);
        return $list;
    }

    public function findArticlesByTag($tagId) {
        $res = $this->ad->findArticlesByTag($tagId);
        if (empty($res)) {
            throw new MyException(0, "There is no article under the target tag.");
        }
        return $res;
    }


    public function showPreOrNextArticle($curId) {
        // 上一条
        $preSql = "select id, title, summary, tags from t_article WHERE id < ? ORDER BY id DESC LIMIT 1";
        // 下一条
        $nextSql = "SELECT id, title, summary, tags FROM t_article WHERE id > ? ORDER BY id ASC LIMIT 1";

        $preRes = MyDB::select($preSql, [$curId]);
        $nextRes = MyDB::select($nextSql, [$curId]);

        $resultList = [];

        if (!empty($preRes)) {
            $article = ORMUtil::db2one(Article::class, $preRes);
            $article->tags = self::tagToArray($article->tags);
            $resultList["pre"] = $article;
        }
        if (!empty($nextRes)) {
            $article = ORMUtil::db2one(Article::class, $nextRes);
            $article->tags = self::tagToArray($article->tags);
            $resultList["next"] = $article;
        }
        return $resultList;
    }


    // 首页的正文列表显示有限个字符
    // 使用mb_substr() 函数, 而非substr() 函数, 解决中文乱码问题
    private static function subStr($str) {
        $subStr = mb_substr($str, 0, 250, "utf-8");
        return $subStr;
    }

    // tag_id  -> tag_name, 结果为: name1 name2 name3...
    private static function tagToArray($tags) {
        $idArr = explode("|", $tags);
        $tagNames = "";
        $tagService = new TagService();
        foreach ($idArr as $id) {
            $tagName = $tagService->getNameById($id);
            $tagNames = $tagNames . $tagName . " ";
        }
        return $tagNames;
    }


    // 格式显示日期
    private static function dateFormat($time) {
        $timestamp = strtotime($time);
        $timeArr = getdate($timestamp);
        // 月份不足10的, 补零
        if ($timeArr["mday"] < 10) {
            $strTime = "0" . $timeArr["mday"] . " " . $timeArr["month"] . " " . $timeArr["year"];
        } else {
            $strTime = "" . $timeArr["mday"] . " " . $timeArr["month"] . " " . $timeArr["year"];
        }
        return $strTime;
    }




}




