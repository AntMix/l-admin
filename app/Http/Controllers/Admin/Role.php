<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;

class Role extends Base
{
    public function list()
    {
        $page = $this->request->get('page');
        $limit = $this->request->get('limit');
        $where = [];
        $id = $this->request->get('id');
        $name = $this->request->get('name');
        $name && $where = ['name' => ['like', "%$name%"]];
        $id && $where = ['id' => $id];
        $data = DB::table('admin_role')->where($where)->page($page)->paginate($limit)->toArray();
        $this->success($data);
    }

    public function info()
    {
        $id = $this->request->get('id');
        if (!$id) {
            return $this->error();
        }
        $data = DB::table('admin_role')->where('id', $id)->first();
        $this->success($data);
    }

    public function power()
    {
        $id = $this->request->get('id');
        $data = [];
        $data['list'] = DB::table('admin_power')->field('id,name,pid,icon,is_menu')->where('status', 1)->select();
        $data['checked'] = DB::table('admin_role_power')->where('role_id', $id)->pluck('power_id');
        $this->success($data);
    }

    public function save()
    {
        $id = $this->request->post('id');
        $power = $this->request->post('power');
        $power && $power = explode(',', $power);
        $data = [
            'name' => $this->request->post('name'),
            'status' => $this->request->post('status', 0),
        ];
        if ($id == 1 && $data['status'] == 0) {
            return $this->error('无法变更超级管理员状态');
        }
        DB::beginTransaction();
        $res1 = $res2 = $res3 = false;
        if ($id) {
            $data['update_time'] = time();
            $res1 = DB::table('admin_role')->where('id', $id)->update($data);
        } else {
            $data['create_time'] = time();
            $res1 = $id = DB::table('admin_role')->insertGetId($data);
        }
        if ($res1 !== false) {
            if (!$power) {
                $res2 = DB::table('admin_role_power')->where('role_id', $id)->delete();
                $res3 = true;
            } else {
                $res2 = DB::table('admin_role_power')->where('role_id', $id)->delete();
                $rolePower = [];
                foreach ($power as $value) {
                    $rolePower[] = [
                        'role_id' => $id,
                        'power_id' => $value
                    ];
                }
                $res3 = DB::table('admin_role_power')->insert($rolePower);
            }
        }
        if ($res1 !== false && $res2 !== false && $res3 !== false) {
            DB::commit();
            return $this->success();
        } else {
            DB::rollback();
            return $this->error();
        }
    }

    public function changeStatus()
    {
        $id = $this->request->input('id');
        if ($id == 1) {
            return $this->error('无法变更超级管理员状态');
        }
        $res = DB::select("update admin_role set status=abs(status-1) where id = $id");
        if ($res !== false) {
            return $this->success();
        } else {
            return $this->error();
        }
    }
}
