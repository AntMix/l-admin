<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminAuth;
use Illuminate\Support\Facades\DB;

class Index extends Base
{
    public function menu()
    {
        $powerIds = DB::table('admin_role_power')->whereIn('role_id', AdminAuth::getUserRoleId($this->uid))->pluck('power_id');
        if (!$powerIds) {
            return $this->error([], '异常：没有设置角色组菜单');
        }
        $menu = DB::table('admin_power')->where(['status' => 1, 'is_menu' => 1])->whereIn('id', $powerIds)->select()->get()->toArray();
        $menu = Power::getMenu($menu);
        $power = DB::table('admin_power')->where(['status' => 1, 'is_menu' => 0, ['sign' , '<>', '']])->whereIn('id', $powerIds)->pluck('sign');
        return $this->success(['menu' => $menu, 'power' => $power]);
    }
}
