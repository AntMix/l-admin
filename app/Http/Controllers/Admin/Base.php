<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuth;
use Illuminate\Http\Request;

class Base extends Controller
{
    protected $uid = 0;
    protected $request;

    public function __construct(Request $request)
    {
        $this->middleware('admin.auth');
        $this->uid = AdminAuth::getUid();
        $this->request = $request;
    }
}
