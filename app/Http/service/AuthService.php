<?php

namespace App\Http\service;

use App\Http\Dao\AuthDao;
use App\Http\Exception\MyException;
use App\Http\Models\Admin;
use App\Http\utils\ORMUtil;

class AuthService implements Service {

    private $ad;

    public function __construct() {
        $this->ad = new AuthDao();
    }


    public function signIn($formAdmin) {
        $findByName = $this->ad->findAdminByName($formAdmin->name);
        if (empty($findByName)) {
            throw new MyException(0, "用户名错误");
        }
        $res = $this->ad->findAdminByNameAndPassword($formAdmin->name, md5($formAdmin->password));
        if (empty($res)) {
            throw new MyException(0, "密码错误");
        }

        $admin = ORMUtil::db2one(Admin::class, $res);

        // 校验通过, 返回查询出的admin
        return $admin;
    }

    public function delete($id) {
        // TODO: Implement delete() method.
    }

    public function show($id) {
        // TODO: Implement show() method.
    }
}


