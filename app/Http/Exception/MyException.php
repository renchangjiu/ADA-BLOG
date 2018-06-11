<?php

namespace App\Http\Exception;


class MyException extends \Exception {

    // 状态码, , 0: failed
    private $status;
    // 携带的数据
    private $data;

    /**
     * MyException constructor.
     * @param $status : 状态码
     * @param $data : 数据
     */
    public function __construct($status, $data) {
        $this->status = $status;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }



}