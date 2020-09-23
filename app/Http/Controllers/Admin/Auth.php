<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class Auth extends Controller
{
    public function doLogin(Request $request)
    {
        $name = $request->post('name');
        $password = $request->post('password');
        $remember = $request->boolean('remember');
        if (!$name || !$password) {
            return $this->error('参数错误');
        }
        $expire = $remember ? 86400 * 7 : 86400 * 1;
        $user = \App\Models\AdminAuth::login($name, $password, $expire);
        if ($user) {
            return $this->success(['link' => '/admin/index', 'user' => $user], '登录成功');
        }
        return $this->error('登录失败，用户名和密码不正确');
    }

    public function logout()
    {
        \App\Models\AdminAuth::logout();
        return $this->success('退出登录成功');
    }
}
