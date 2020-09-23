<?php

namespace App\Http\Middleware;

use Closure;
use App\Lib\JWT;
use App\Models\AdminAuth;
use App\Models\AdminUser;
use Illuminate\Support\Facades\DB;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->cookie(AdminAuth::COOKIE_NAME);
        if (!$token) {
            return redirect()->route('adminLogin');
        }
        $info = JWT::verifyToken($token);
        if (isset($info['uid']) && $info['uid']) {
            $user = AdminUser::getNormalUser($info['uid']);
            if (!$user) {
                return redirect()->route('adminLogin');
            }
        } else {
            return redirect()->route('adminLogin');
        }

        $roleIds = AdminAuth::getUserRoleId($user['id']);
        if (!$roleIds) {
            return redirect()->route('adminLogin');
        }
        $powerId = DB::table('admin_power')->where([
            'path' => str_replace($request->route()->getPrefix() . '/', '', $request->path()),
            'status' => 1
        ])->value('id');
        if ($powerId) {
            $access = DB::table('admin_role_power')->whereIn('role_id', $roleIds)->where('power_id', $powerId)->first();
            if (!$access) {
                abort(403);
            }
        }
        return $next($request);
    }
}
