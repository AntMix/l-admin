<?php

use Illuminate\Support\Facades\Route;

Route::post('auth/doLogin', 'Admin\Auth@doLogin');

Route::get('index/menu', 'Admin\Index@menu');

Route::get('power/list', 'Admin\Power@list');
Route::get('power/info', 'Admin\Power@info');
Route::post('power/changeStatus', 'Admin\Power@changeStatus');
Route::post('power/changeIsMenu', 'Admin\Power@changeIsMenu');
Route::post('power/save', 'Admin\Power@save');
Route::post('power/delete', 'Admin\Power@delete');

Route::get('admin_user/list', 'Admin\AdminUser@list');
Route::get('admin_user/info', 'Admin\AdminUser@info');
Route::post('admin_user/save', 'Admin\AdminUser@save');
Route::post('admin_user/delete', 'Admin\AdminUser@delete');
Route::get('admin_user/role', 'Admin\AdminUser@role');
Route::post('admin_user/changeStatus', 'Admin\AdminUser@changeStatus');
Route::post('admin_user/changePassword', 'Admin\AdminUser@changePassword');
Route::post('admin_user/batchEnable', 'Admin\AdminUser@batchEnable');
Route::post('admin_user/batchDisabled', 'Admin\AdminUser@batchDisabled');
Route::post('admin_user/batchDel', 'Admin\AdminUser@batchDel');

Route::get('personal/info', 'Admin\Personal@info');
Route::post('personal/save', 'Admin\Personal@save');
Route::post('personal/changePassword', 'Admin\Personal@changePassword');

Route::get('role/list', 'Admin\Role@list');
Route::get('role/info', 'Admin\Role@info');
Route::get('role/power', 'Admin\Role@power');
Route::post('role/save', 'Admin\Role@save');
Route::post('role/changeStatus', 'Admin\Role@changeStatus');

Route::post('file/image', 'Admin\file@image');

Route::get('test', 'Admin\Test@index');
