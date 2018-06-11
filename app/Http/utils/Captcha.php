<?php

namespace App\Http\utils;


use stdClass;

class Captcha {

    // 返回验证码图片转base64后的字符串及验证码
    public static function getCaptcha() {

        $charArray = array_merge(range("A", "Z"), range("a", "z"), range(0, 9));
        $index = array_rand($charArray, 4);     // 随机取4个字符, 返回字符的下标
        shuffle($index);    // 打乱下标
        // 拼接字符串
        $str = "";
        foreach ($index as $i) {
            $str .= $charArray[$i];
        }

        $img = imagecreate(145, 20);
        imagecolorallocate($img, 255, 250, 250);    // 背景色
        $color = imagecolorallocate($img, 0, 0, 0); // 字符色

        $font = 5;  // 字号
        $x = (imagesx($img) - imagefontwidth($font) * strlen($str)) / 2;
        $y = (imagesy($img) - imagefontheight($font)) / 2;
        imagestring($img, $font, $x, $y, $str, $color);     // 将字符串写入图片

        ob_start();
        // imagejpeg($img);
        imagepng($img);
        // 获取缓冲区数据
        $imageData = ob_get_contents();
        ob_end_clean();
        // base64
        $imageBase64 = base64_encode($imageData);

        $obj = new stdClass();
        $obj->imgBase64 = $imageBase64;
        $obj->str = $str;

        return $obj;
    }


}