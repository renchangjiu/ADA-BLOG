<?php

namespace App\Http\Models;


class Page {
    public $totalPage;
    public $curPage;
    public $objects;
    public $pageSize;
    public $totalCount;

    /**
     * Page constructor.
     * @param $curPage
     * @param $pageSize
     * @param $totalCount
     */
    public function __construct($curPage, $pageSize, $totalCount) {
        $this->totalPage = ceil($totalCount / $pageSize);
        $this->curPage = $curPage;
        $this->pageSize = $pageSize;
        $this->totalCount = $totalCount;
    }

    /**
     * 根据curPage 返回mysql: limit index, length 中的index
     */
    public function getIndex() {
        return ($this->curPage - 1) * $this->pageSize;
    }

    /**
     * @return float
     */
    public function getTotalPage(): float {
        return $this->totalPage;
    }

    /**
     * @param float $totalPage
     */
    public function setTotalPage(float $totalPage): void {
        $this->totalPage = $totalPage;
    }

    /**
     * @return mixed
     */
    public function getCurPage() {
        return $this->curPage;
    }

    /**
     * @param mixed $curPage
     */
    public function setCurPage($curPage): void {
        $this->curPage = $curPage;
    }

    /**
     * @return mixed
     */
    public function getObjects() {
        return $this->objects;
    }

    /**
     * @param mixed $objects
     */
    public function setObjects($objects): void {
        $this->objects = $objects;
    }

    /**
     * @return mixed
     */
    public function getPageSize() {
        return $this->pageSize;
    }

    /**
     * @param mixed $pageSize
     */
    public function setPageSize($pageSize): void {
        $this->pageSize = $pageSize;
    }

    /**
     * @return mixed
     */
    public function getTotalCount() {
        return $this->totalCount;
    }

    /**
     * @param mixed $totalCount
     */
    public function setTotalCount($totalCount): void {
        $this->totalCount = $totalCount;
    }



}