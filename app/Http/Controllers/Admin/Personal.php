<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Model\AdminAuth;
use App\Model\AdminUser;

class Personal extends Base
{
    public function info()
    {
        $data = AdminUser::dealInfo(AdminUser::getNormalUser($this->uid));
        $this->success($data);
    }

    public function save()
    {
        $data = [
            'nick' => $this->request->post('nick'),
            'phone' => $this->request->post('phone'),
            'email' => $this->request->post('email')
        ];
        if (!AdminAuth::checkNickFormat($data['nick'])) {
            return $this->error('昵称格式错误');
        }
        $res = DB::table('admin_user')->where('id', $this->uid)->update($data);
        if ($res !== false) {
            $this->success();
        } else {
            $this->error();
        }
    }

    public function changePassword()
    {
        $user = DB::table('admin_user')->where('id', $this->uid)->field('password')->first();
        if (!$user) {
            return $this->error('没有查询到此用户');
        }
        $oldPass = $this->request->post('old_pass');
        if (!AdminAuth::password($oldPass, $user['password'])) {
            return $this->error('旧密码错误');
        }
        $password = $this->request->post('password');
        $password = AdminAuth::password($password);
        if (!$password) {
            return $this->error('密码格式错误');
        }
        $res = DB::table('admin_user')->where('id', $this->uid)->update(['password' => $password]);
        if ($res !== false) {
            return $this->success();
        } else {
            return $this->error();
        }
    }
}
