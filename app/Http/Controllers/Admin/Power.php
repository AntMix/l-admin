<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Power extends Base
{
    public function list()
    {
        $isMenu = $this->request->input('is_menu', 0);
        $where = [['status', '<>', -1]];
        $isMenu && $where['is_menu'] = 1;
        $children = $this->request->input('children', 0);
        $data = DB::table('admin_power')->where($where)->select()->get()->toArray();
        if ($children) {
            $data = self::getMenu($data);
        }
        return $this->success($data);
    }

    public static function getMenu($data)
    {
        $menu = [];
        foreach ($data as $key => $value) {
            $item = $value;
            if ($value->pid === 0) {
                unset($data[$key]);
                $children = self::getMenuChildren($item, $data);
                if ($children) {
                    $item->children = $children;
                } else {
                    self::setMenuValue($item, 'href', $item->path);
                }
                $menu[] = $item;
            }
        }
        return $menu;
    }

    protected static function getMenuChildren($parent, &$data)
    {
        $menu = [];
        foreach ($data as $key => $value) {
            $item = $value;
            if ($value->pid === $parent->id) {
                unset($data[$key]);
                $children = self::getMenuChildren($item, $data);
                if ($children) {
                    $item->children = $children;
                }
                self::setMenuValue($item, 'href', $item->path);
                $menu[] = $item;
            }
        }
        return $menu;
    }

    protected static function setMenuValue(&$item, $key, $value)
    {
        $item->$key = $value;
    }

    public function info()
    {
        $id = $this->request->input('id');
        $data = DB::table('admin_power')->where('id', $id)->first();
        return $this->success($data);
    }

    public function changeStatus()
    {
        $id = $this->request->input('id');
        $res = DB::select("update admin_power set status=abs(status-1) where id = $id");
        if ($res !== false) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function changeIsMenu()
    {
        $id = $this->request->input('id');
        $res = DB::select("update admin_power set is_menu=abs(is_menu-1) where id = $id");
        if ($res !== false) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function save()
    {
        $id = $this->request->post('id');
        $data = [
            'name' => $this->request->post('name'),
            'path' => $this->request->post('path'),
            'pid' => $this->request->post('pid'),
            'icon' => $this->request->post('icon'),
            'sign' => $this->request->post('sign'),
            'is_menu' => $this->request->post('is_menu', 0),
            'status' => $this->request->post('status', 0)
        ];
        if ($data['pid']) {
            $parent = DB::table('admin_power')->where('id', $data['pid'])->where('is_menu', 1)->first();
            if (!$parent) {
                return $this->error();
            }
        }
        if ($id) {
            $data['update_time'] = time();
            $res = DB::table('admin_power')->where('id', $id)->update($data);
        } else {
            $data['create_time'] = time();
            $res = DB::table('admin_power')->insertGetId($data);
        }
        if ($res !== false) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function delete()
    {
        $id = $this->request->post('id');
        $item = DB::table('admin_power')->where(['id' => $id])->first();
        $updateTime = time();
        $update = ['status' => -1, 'update_time' => $updateTime];
        if (!$item) {
            return $this->error('没有此记录');
        }
        DB::beginTransaction();
        $res = DB::table('admin_power')->where(['id' => $id])->update($update);
        if ($res !== false) {
            $children = [];
            $pid = [$id];
            while (true) {
                $children = DB::table('admin_power')->whereIn('pid', $pid)->pluck('id');
                if (!$children) {
                    DB::commit();
                    return $this->success();
                } else {
                    $pid = $children;
                    DB::table('admin_power')->whereIn('id', $children)->update($update);
                }
            }
        } else {
            DB::rollback();
            return $this->error();
        }
    }
}
