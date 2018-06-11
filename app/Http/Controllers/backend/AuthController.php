<?php

namespace App\Http\Controllers\backend;

use App\Http\Exception\MyException;
use App\Http\Models\Admin;
use App\Http\Models\Result;
use App\Http\service\AuthService;
use App\Http\utils\IpUtil;
use App\Http\utils\ORMUtil;
use App\Http\utils\UUID;
use Illuminate\Http\Request;
use \App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

// 管理员登入, 登出, 注册, 修改密码等

class AuthController extends Controller {


    public function signIn(Request $request, AuthService $service) {
        if ($request->has("name") && $request->has("password")) {
            $formAdmin = ORMUtil::input2Obj(Admin::class, $request->all());

            $remember = $request->input("remember");    // 选中则值为 on
            $captchaId = $request->input("captchaId");
            $captcha = $request->input("captcha");

            $ip = IpUtil::getIp();

            try {
                $admin = $service->signIn($formAdmin);
                // 验证码
                if ($this->validateCaptcha($captchaId, $captcha, $formAdmin->name, $ip)) {
                    // 校验通过, 用户信息写入redis, key: token, value: user, 设置过期时间
                    $token = UUID::uuid();
                    $adminJson = json_encode($admin);
                    Redis::set("TOKEN:$token", $adminJson);
                    Redis::expire("TOKEN:$token", 60 * 60 * 24);
                    $this->setNotNeedCaptcha($formAdmin->name, $ip);
                    // 返回token
                    return response()->json(Result::success($token));
                } else {
                    return response()->json(Result::failed("验证码错误"));
                }
            } catch (MyException $e) {
                // 如果登录失败, 则设置需要验证码, 方法是: 在Redis设置一个key为 "username;ip", value为1的键值对
                $this->setNeedCaptcha($formAdmin->name, $ip);
                return response()->json(Result::failed($e->getData()));
            }
        } else {
            return response()->json(Result::failed("请填写用户名或密码或验证码"));
        }
    }

    /**
     * @param $username string 登陆时使用的用户名
     */
    private function setNeedCaptcha($username, $ip) {
        Redis::set("need-captcha:$username;$ip", "yes");
        Redis::expire("need-captcha:$username;$ip", 30);
    }

    private function setNotNeedCaptcha($username, $ip) {
        Redis::del("need-captcha:$username;$ip");
    }


    /** 判断当前登录人是否需要验证码
     * @param $username string 登陆时使用的用户名
     * @return bool true or false
     */
    private function isNeedCaptcha($username, $ip) {
        if (Redis::exists("need-captcha:$username;$ip")) {
            return true;
        }
        return false;
    }

    /**
     * @param $captchaId
     * @param $captcha
     * @param $username string 登录名
     * @param $ip string 登录ip
     * @return bool
     */
    private function validateCaptcha($captchaId, $captcha, $username, $ip) {
        $flag = false;
        if ($this->isNeedCaptcha($username, $ip)) {
            if (Redis::exists("captchaId:$captchaId")) {
                $str = Redis::get("captchaId:$captchaId");
                if (strcasecmp($str, $captcha) == 0) {
                    $flag = true;
                }
            }
        } else {
            $flag = true;
        }
        return $flag;
        // 不用验证码的话, 就直接return true
        // return true;
    }

    // 登出
    public function signOut(Request $request) {
        if ($request->hasHeader("token")) {
            $token = $request->header("token");
            $res = Redis::del("TOKEN:$token");
            if ($res == 1) {
                return response()->json(Result::failed(null, "登出成功"));
            } else {
                return response()->json(Result::failed(null, "登出成功, 但当前没有登录"));
            }
        } else {
            return response()->json(Result::success(null, "登出成功, 但缺少header:token"));
        }
    }


}
