<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Model{
/**
 * App\Model\AdminUser
 *
 * @property int $id
 * @property string $name
 * @property string $nick
 * @property string $password
 * @property string $phone
 * @property string $email
 * @property int $status
 * @property int $delete_time
 * @property int $create_time
 * @property int $update_time
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereCreateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereDeleteTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereNick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser whereUpdateTime($value)
 */
	class AdminUser extends \Eloquent {}
}

