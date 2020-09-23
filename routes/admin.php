<?php

use Illuminate\Support\Facades\Route;

Route::redirect('auth/login', 'auth/login')->name('adminLogin');
Route::post('auth/doLogin', 'Admin\Auth@doLogin');
Route::get('auth/logout', 'Admin\Auth@logout');

Route::get('index/menu', 'Admin\Index@menu');

Route::get('power/list', 'Admin\Power@list');
Route::get('power/info', 'Admin\Power@info');
Route::post('power/changeStatus', 'Admin\Power@changeStatus');
Route::post('power/changeIsMenu', 'Admin\Power@changeIsMenu');
Route::post('power/save', 'Admin\Power@save');
Route::post('power/delete', 'Admin\Power@delete');

Route::get('admin_user/list', 'Admin\Admin@list');
Route::get('admin_user/info', 'Admin\Admin@info');
Route::post('admin_user/save', 'Admin\Admin@save');
Route::post('admin_user/delete', 'Admin\Admin@delete');
Route::get('admin_user/role', 'Admin\Admin@role');
Route::post('admin_user/changeStatus', 'Admin\Admin@changeStatus');
Route::post('admin_user/changePassword', 'Admin\Admin@changePassword');
Route::post('admin_user/batchEnable', 'Admin\Admin@batchEnable');
Route::post('admin_user/batchDisabled', 'Admin\Admin@batchDisabled');
Route::post('admin_user/batchDel', 'Admin\Admin@batchDel');

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
