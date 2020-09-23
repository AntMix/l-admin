<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminUser;
use Illuminate\Support\Facades\DB;

class Role extends Base
{
    public function list()
    {
        $limit = $this->request->get('limit');
        $where = [];
        $id = $this->request->get('id');
        $name = $this->request->get('name');
        $name && $where = ['name' => ['like', "%$name%"]];
        $id && $where = ['id' => $id];
        $data = DB::table('admin_role')->where($where)->paginate($limit);
        $data = $this->toArray($data);
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
        $data['list'] = DB::table('admin_power')->where('status', 1)->select(['id','name','pid','icon','is_menu'])->get()->toArray();
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
        if ($id == AdminUser::SUPER_ID && $data['status'] == 0) {
            return $this->error('无法变更超级管理员状态');
        }
        DB::beginTransaction();
        try {
            if ($id) {
                $data['update_time'] = time();
                DB::table('admin_role')->where('id', $id)->update($data);
            } else {
                $data['create_time'] = time();
                $id = DB::table('admin_role')->insertGetId($data);
            }
            if (!$power) {
                DB::table('admin_role_power')->where('role_id', $id)->delete();
            } else {
                DB::table('admin_role_power')->where('role_id', $id)->delete();
                $rolePower = [];
                foreach ($power as $value) {
                    $rolePower[] = [
                        'role_id' => $id,
                        'power_id' => $value
                    ];
                }
                DB::table('admin_role_power')->insert($rolePower);
            }
            DB::commit();
            return $this->success();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error($e->getMessage());
        }
    }

    public function changeStatus()
    {
        $id = $this->request->input('id');
        if ($id == AdminUser::SUPER_ID) {
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
