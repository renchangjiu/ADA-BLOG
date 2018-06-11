<?php

namespace App\Http\service;


use App\Http\Dao\SignInLogDao;
use App\Http\Exception\MyException;
use App\Http\Models\Page;
use App\Http\Models\SignInLog;
use App\Http\utils\ORMUtil;

class SignInLogService implements Service {

    private $sld;

    public function __construct() {
        $this->sld = new SignInLogDao();
    }


    public function list($curPage) {
        $totalCount = $this->getTotalCount();
        $pageSize = 20;
        $paginator = new Page($curPage, $pageSize, $totalCount);
        if ($curPage > $paginator->getTotalPage()) {
            throw new MyException(0, "The target page number exceeds the maximum page number.");
        }
        $list = ORMUtil::db2list(SignInLog::class, $this->sld->list($paginator->getIndex(), $paginator->getPageSize()));
        $paginator->setObjects($list);
        return $paginator;
    }

    public function getTotalCount() {
        return $this->sld->getTotalCount();
    }


    public function delete($id) {
    }

    public function update($id) {
    }

    public function show($id) {
    }
}