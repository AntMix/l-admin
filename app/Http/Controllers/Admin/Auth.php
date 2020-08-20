<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Auth extends Controller
{
    public function doLogin()
    {
        $name = $this->request->post('name');
        $password = $this->request->post('password');
        $remember = $this->request->post('remember');
        if (!$name || !$password) {
            return $this->error('参数错误');
        }
        $expire = $remember ? 86400 * 7 : 86400 * 1;
        $user = \App\Model\AdminAuth::login($name, $password, $expire);
        if ($user) {
            return $this->success(['link' => '/admin/index', 'user' => $user], '登录成功');
        }
        return $this->error('登录失败，用户名和密码不正确');
    }
}
