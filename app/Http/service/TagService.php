<?php

namespace App\Http\service;


use App\Http\Dao\TagDao;
use App\Http\Exception\MyException;
use App\Http\Models\Page;
use App\Http\utils\MyDB;

class TagService implements Service {

    private $td;

    public function __construct() {
        $this->td = new TagDao();
    }

    public function getNameById($tagId) {
        $sql = "select * from t_tag where id = ?";
        $res = MyDB::select($sql, [$tagId]);
        $tagName = $res[0]->name;
        return $tagName;
    }

    public function list() {
        return $this->td->list();
    }

    public function getTotalCount() {
        return $this->td->getTotalCount();
    }


    public function insert($tag) {
        $res = $this->td->insert($tag);
        if (!$res) {
            throw new MyException(0, "插入失败");
        }
    }


    public function delete($id) {
        $res = $this->td->delete($id);
        if (!$res) {
            throw new MyException(0, "删除失败");
        }
    }

    public function show($id) {
        // TODO: Implement show() method.
    }
}

