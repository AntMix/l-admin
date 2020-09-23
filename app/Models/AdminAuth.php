<?php

namespace App\Models;

use Illuminate\Support\Facades\Cookie;
use App\Models\AdminUser;
use App\Lib\JWT;
use DB;
use Request;

class AdminAuth
{
    protected $table = 'admin_auth';
    const SALT = 'adminSalt';
    const COOKIE_NAME = 'admin_auth';
    const LOGIN_PAGE = '/admin/auth/login';

    public static function password($password, $vPass = '')
    {
        if (self::checkPassFormat($password)) {
            $password = md5(md5($password . '@' . self::SALT));
            return $vPass ? $vPass === $password ? true : false : $password;
        }
        return false;
    }

    public static function checkNameFormat($name)
    {
        if (preg_match("/^[A-Za-z0-9_\S]{5,20}+$/", $name)) {
            return true;
        }
        return false;
    }

    public static function checkNickFormat($nick)
    {
        if (mb_strlen($nick) <= 20) {
            return true;
        }
        return false;
    }

    public static function checkPassFormat($password)
    {
        if (preg_match("/^[\S]{6,12}$/", $password)) {
            return true;
        }
        return false;
    }

    public static function login($name, $password, $expire)
    {
        $time = time();
        $password = self::password($password);
        $user = AdminUser::where(['name' => $name, 'password' => $password])->where(AdminUser::$normal)->first();
        if (!$user) {
            return false;
        }
        $token = JWT::getToken([
            'iss' => 'roopto',  //该JWT的签发者
            'iat' => $time,  //签发时间
            'exp' => $time + $expire,  //过期时间
            'uid' => $user['id']
        ]);
        // cookie($name, $value, $minutes, $path, $domain, $secure, $httpOnly)
        Cookie::queue(self::COOKIE_NAME, $token, $expire / 60, '/', '', false, false);
        return AdminUser::dealInfo($user);;
    }

    public static function logout()
    {
        Cookie::queue(self::COOKIE_NAME, '', -1);
    }

    public static function getUserRoleId($uid)
    {
        return DB::table('admin_role_user')->where(['uid' => $uid, 'status' => 1])->pluck('role_id')->toArray();
    }

    public static function getUser()
    {
        $user = AdminUser::getNormalUser(self::getUid());
        return AdminUser::dealInfo($user);
    }

    public static function getUid()
    {
        return JWT::verifyToken(Request::cookie(self::COOKIE_NAME))['uid'];
    }
}
