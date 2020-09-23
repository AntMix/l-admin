<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    protected $table = 'admin_user';

    public static $normal = ['status' => 1];

    public static function getNormalUser($id, $field = '*')
    {
        return self::where('id', $id)->where(self::$normal)->first($field)->toArray();
    }

    public static function dealInfo($user)
    {
        unset($user['password']);
        return $user;
    }
}