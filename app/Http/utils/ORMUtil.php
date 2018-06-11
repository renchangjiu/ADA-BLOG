<?php

namespace App\Http\utils;


class ORMUtil {

    /**
     * 将数据库里取出的数据映射到对象, 该结果集只有一条结果
     * 注意: 数据库的字段名与类的属性名必须相同
     * @param $objName : 要实例化的对象, 全限定名(命名空间\类型::class)
     * @param $res : 使用Laravel 框架中的DB类获得的结果集
     * @return mixed : 映射完成的对象
     */
    public static function db2one($objClass, $res) {
        $obj = new $objClass;
        foreach ($res[0] as $key => $value) {
            $obj->$key = $value;
        }
        return $obj;
    }


    /**
     * 将数据库里取出的数据映射到对象, 该结果集有多条结果
     * * 注意: 数据库的字段名与类的属性名必须相同
     * @param $objName : 要实例化的对象, 全限定名(命名空间\类型::class)
     * @param $res : 使用Laravel 框架中的DB类获得的结果集
     * @return mixed : 映射完成的对象数组
     */
    public static function db2list($objClass, $res) {
        $list = [];
        foreach ($res as $row) {
            $obj = new $objClass;
            foreach ($row as $key => $value) {
                $obj->$key = $value;
            }
            $list[] = $obj;
        }
        return $list;
    }

    /**
     * 将用户提交的数据映射到对象
     * * 注意: 字段名与类的属性名必须相同
     * @param $objClass : 要实例化的对象, 全限定名(命名空间\类型::class)
     * @param $inputs : 提交的数据
     * @return mixed : 映射完成的对象
     */
    public static function input2Obj($objClass, $inputs) {
        $obj = new $objClass;
        foreach ($obj as $name => &$value) {    // 传引用
            foreach ($inputs as $key => $v) {
                if ($name == $key) {
                    $value = $v;
                    break;
                }
            }
        }
        return $obj;
    }


}
