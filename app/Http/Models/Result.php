<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\Request;



/**
 * The result of the interaction between the front and back ends.
 * @package App\Http\Models
 * implements Traversable
 */
class Result {
    /* 状态码:
    450: 文章-请求的页码超过最大页码


     */


    // 是否成功
    public $success;
    // 状态码
    public $status;
    // 提示信息
    public $message;
    // 携带的数据
    public $data;

    /**
     * Result constructor.
     * @param $success
     * @param $status
     * @param $message
     * @param $data
     */
    private function __construct($success, $data, $message, $status) {
        $this->success = $success;
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    public static function success($data = null, $message = "", $status = 1) {
        return new Result(true, $data, $message, $status);
    }


    public static function failed($data = null, $message = "", $status = 0) {
        return new Result(false, $data, $message, $status);
    }

    public static function getResult($success, $data = null, $message = "", $status = 0) {
        return new Result($success, $data, $message, $status);
    }


    /**
     * Result constructor.
     * @param $status
     * @param $message
     * @param $data
     */

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void {
        $this->data = $data;
    }


}
