<?php

namespace App\Http\service;


use App\Http\Dao\ReadLogDao;
use App\Http\Exception\MyException;
use App\Http\Models\Page;
use App\Http\Models\ReadLog;
use App\Http\utils\ORMUtil;

class ReadLogService implements Service {

    private $rld;

    public function __construct() {
        $this->rld = new ReadLogDao();
    }


    public function list($curPage) {
        $totalCount = $this->getTotalCount();
        $pageSize = 20;
        $paginator = new Page($curPage, $pageSize, $totalCount);
        if ($curPage > $paginator->getTotalPage()) {
            throw new MyException(0, "The target page number exceeds the maximum page number.");
        }
        $letters = ORMUtil::db2list(ReadLog::class, $this->rld->list($paginator->getIndex(), $paginator->getPageSize()));
        $paginator->setObjects($letters);
        return $paginator;
    }

    public function getTotalCount() {
        return $this->rld->getTotalCount();
    }


    public function delete($id) {
    }

    public function update($id) {
    }

    public function show($id) {
    }
}