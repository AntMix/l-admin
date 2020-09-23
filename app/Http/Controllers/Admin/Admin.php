<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Models\AdminAuth;
use App\Lib\Util;
use App\Models\AdminUser;

class Admin extends Base
{
    public function list()
    {
        $limit = $this->request->get('limit');
        $id = $this->request->get('id');
        $name = $this->request->get('name');
        $nick = $this->request->get('nick');
        $phone = $this->request->get('phone');
        $email = $this->request->get('email');
        $startTime = $this->request->get('start_time');
        $endTime = $this->request->get('end_time');
        $queryTime = Util::queryTime($startTime, $endTime, 'create_time');
        $where = [];
        $name && $where[] = ['name', 'like', "%$name%"];
        $nick && $where[] = ['nick', 'like', "%$nick%"];
        $phone && $where[] = ['phone', 'like', "%$phone%"];
        $email && $where[] = ['email', 'like', "%$email%"];
        $queryTime && $where[] = $queryTime;
        $id && $where = ['id' => $id];
        $data = DB::table('admin_user')->where($where)->paginate($limit);
        $data = $this->toArray($data);
        foreach ($data['data'] as &$value) {
            $value['role'] = DB::table('admin_role_user as ru')->join('admin_role as r', 'ru.role_id', '=', 'r.id', 'left')->where('ru.uid', $value['id'])->pluck('r.name')->toArray();
            $value['role'] = $value['role'] ? implode(' | ', $value['role']) : '';
        }
        $this->success($data);
    }

    public function info()
    {
        $id = $this->request->get('id');
        $data = DB::table('admin_user')->where('id', $id)->first();
        $this->success($data);
    }

    public function save()
    {
        $id = $this->request->post('id');
        $data = [
            'name' => $this->request->post('name'),
            'nick' => $this->request->post('nick'),
            'phone' => $this->request->post('phone', ''),
            'email' => $this->request->post('email', ''),
            'status' => $this->request->post('status', 0),
        ];
        $role = $this->request->post('role');
        if (!$role) {
            return $this->error('必须设置用户组');
        }
        $role = explode(',', $role);
        if (!AdminAuth::checkNameFormat($data['name'])) {
            return $this->error('用户名格式错误');
        }
        if (!AdminAuth::checkNickFormat($data['nick'])) {
            return $this->error('昵称格式错误');
        }
        $time = time();
        if ($id == AdminUser::SUPER_ID && !$data['status']) {
            return $this->error('无法修改管理员状态');
        }
        DB::beginTransaction();
        try {
            if ($id) {
                $data['update_time'] = $time;
                DB::table('admin_user')->where('id', $id)->update($data);
            } else {
                $data['create_time'] = $time;
                $data['password'] = AdminAuth::password($this->request->post('password'));
                if (!$data['password']) {
                    return $this->error('密码格式错误');
                }
                $id = DB::table('admin_user')->insertGetId($data);
            }
            if (!$role) {
                DB::table('admin_role_user')->where('uid', $id)->delete();
            } else {
                DB::table('admin_role_user')->where('uid', $id)->delete();
                $rolePower = [];
                foreach ($role as $value) {
                    $rolePower[] = [
                        'uid' => $id,
                        'role_id' => $value
                    ];
                }
                DB::table('admin_role_user')->insert($rolePower);
            }
            DB::commit();
            return $this->success();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error($e->getMessage());
        }
    }

    public function delete()
    {
        $id = $this->request->post('id');
        $item = DB::table('admin_user')->where('id', $id)->first();
        if (!$item) {
            return $this->error('没有记录');
        }
        $res = DB::table('admin_user')->where('id', $id)->update(['status' => -1]);
        if ($res !== false) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function role()
    {
        $id = $this->request->get('id');
        $data['list'] = DB::table('admin_role')->where('status', 1)->select('id as value', 'name')->get()->toArray();
        $data['checked'] = DB::table('admin_role_user')->where('uid', $id)->pluck('role_id');
        $this->success($data);
    }

    public function changeStatus()
    {
        $id = $this->request->input('id');
        if ($id == AdminUser::SUPER_ID) {
            return $this->error('无法修改管理员状态');
        }
        $res = DB::select("update admin_user set status=abs(status-1) where id = $id");
        if ($res !== false) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function changePassword()
    {
        $id = $this->request->post('id');
        $user = DB::table('admin_user')->where('id', $id)->first();
        if (!$user) {
            return $this->error('没有查询到此用户');
        }
        $password = $this->request->post('password');
        $password = AdminAuth::password($password);
        if (!$password) {
            return $this->error('密码格式错误');
        }
        $res = DB::table('admin_user')->where('id', $id)->update(['password' => $password]);
        if ($res !== false) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function batchEnable()
    {
        $ids = $this->request->post('ids');
        $ids && $ids = explode(',', $ids);
        if (!$ids) {
            return $this->error('操作失败 请选择数据');
        }
        unset($ids[array_search(AdminUser::SUPER_ID, $ids)]);
        $res = DB::table('admin_user')->whereIn('id', $ids)->update(['status' => 1]);
        if ($res !== false) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function batchDisabled()
    {
        $ids = $this->request->post('ids');
        $ids && $ids = explode(',', $ids);
        if (!$ids) {
            return $this->error('操作失败 请选择数据');
        }
        unset($ids[array_search(AdminUser::SUPER_ID, $ids)]);
        $res = DB::table('admin_user')->whereIn('id', $ids)->update(['status' => 0]);
        if ($res !== false) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function batchDel()
    {
        $ids = $this->request->post('ids');
        $ids && $ids = explode(',', $ids);
        if (!$ids) {
            return $this->error('操作失败 请选择数据');
        }
        unset($ids[array_search(AdminUser::SUPER_ID, $ids)]);
        $res = DB::table('admin_user')->whereIn('id', $ids)->update(['status' => -1]);
        if ($res !== false) {
            return $this->success();
        } else {
            return $this->error();
        }
    }
}
