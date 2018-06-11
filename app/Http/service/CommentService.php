<?php

namespace App\Http\service;


use App\Http\Dao\CommentDao;
use App\Http\Exception\MyException;
use App\Http\Models\Page;
use App\Http\utils\MyDB;

class CommentService implements Service {
    private $cd;

    public function __construct() {
        $this->cd = new CommentDao();
    }

    // // 返回某文章下所有评论
    public function findListByArticleId($articleId, $curPage) {
        $totalCount = $this->getTotalCountByArtId($articleId);
        if ($totalCount == 0) {
            throw new MyException(0, "The target article has no comment.");
        }
        $pageSize = 5;
        $paginator = new Page($curPage, $pageSize, $totalCount);
        if ($curPage > $paginator->getTotalPage()) {
            throw new MyException(0, "The target page number exceeds the maximum page number.");
        }
        $list = $this->cd->findListByArticleId($articleId, $paginator->getIndex(), $paginator->getPageSize());

        $paginator->setObjects($list);
        return $paginator;

    }


    // 返回所有评论
    public function list($curPage) {
        $totalCount = $this->getTotalCount();
        if ($totalCount == 0) {
            throw new MyException(0, "no comment.");
        }
        $pageSize = 20;
        $paginator = new Page($curPage, $pageSize, $totalCount);
        if ($curPage > $paginator->getTotalPage()) {
            throw new MyException(0, "The target page number exceeds the maximum page number.");
        }
        $list = $this->cd->list($paginator->getIndex(), $paginator->getPageSize());

        $paginator->setObjects($list);
        return $paginator;
    }


    public function getTotalCountByArtId($articleId) {
        return $this->cd->getTotalCountByArticleId($articleId);
    }


    public function getTotalCount() {
        return $this->cd->getTotalCount();
    }

    public function insert($comment) {
        MyDB::beginTransaction();
        $comment->floor = $this->findMaxFloorByArtId($comment->artId);      // 设置应在的楼层
        $res = $this->cd->insert($comment);
        if (!$res) {
            MyDB::rollback();
            throw new MyException(0, "插入评论失败");
        }
        MyDB::commit();
    }

    public function findMaxFloorByArtId($artId) {
        $maxFloor = $this->cd->findMaxFloorByArtId($artId);
        return $maxFloor;
    }


    public function delete($id) {
        $res = $this->cd->delete($id);
        if (!$res) {
            throw new MyException(0, "删除失败");
        }
    }

    public function deleteByArticleId($artId) {
        $this->cd->deleteByArticleId($artId);
    }

    // 查询当前ip上一次提交评论的时间
    public function findLastInsertTimeByIp($ip) {
        $res = $this->cd->findLastInsertTimeByIp($ip);
        // 如果当前ip还没有提交过评论
        if (!$res) {
            return 1;
        } else {
            return $res[0]->time;
        }
    }


    public function show($id) {
        // TODO: Implement show() method.
    }
}