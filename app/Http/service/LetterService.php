<?php

namespace App\Http\service;


use App\Http\Dao\LetterDao;
use App\Http\Exception\MyException;
use App\Http\Models\Letter;
use App\Http\Models\Page;
use App\Http\utils\MyDB;
use App\Http\utils\ORMUtil;

class LetterService implements Service {
    private $ld;

    public function __construct() {
        $this->ld = new LetterDao();
    }

    public function list($curPage) {
        $totalCount = $this->getTotalCount();
        $pageSize = 20;
        $paginator = new Page($curPage, $pageSize, $totalCount);
        if ($curPage > $paginator->getTotalPage()) {
            throw new MyException(0, "The target page number exceeds the maximum page number.");
        }
        $letters = $this->ld->list($paginator->getIndex(), $paginator->getPageSize());
        foreach ($letters as $l) {
            // 如果message 的长度超过40 个字符, 只显示前40 个并追加...
            if (isset($l->message[40])) {
                $l->message = self::subStr($l->message . "...");
            }
        }
        $paginator->setObjects($letters);
        return $paginator;

    }

    public function getTotalCount() {
        return $this->ld->getTotalCount();
    }

    public function insert($letter) {
        $res = $this->ld->insert($letter);
        if (!$res) {
            throw new MyException(0, "插入信件失败");
        }
    }


    public function delete($id) {
        $res = $this->ld->delete($id);
        if ($res != 1) {
            throw new MyException(0, "删除失败");
        }
    }

    public function update($id) {
    }

    public function show($id) {
        $res = $this->ld->show($id);
        if (!empty($res)) {
            return ORMUtil::db2one(Letter::class, $res);
        } else {
            throw new MyException(0, "No data was found");
        }


    }

    // 首页的正文列表显示有限个字符
    // 使用mb_substr() 函数, 而非substr() 函数, 解决中文乱码问题
    private static function subStr($str) {
        $subStr = mb_substr($str, 0, 40, "utf-8");
        return $subStr;
    }
}