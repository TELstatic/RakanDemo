<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * 后台首页
 * @desc 后台首页
 */
class IndexController extends Controller
{
    /**
     * 控制面板
     * @desc 控制面板
     */
    public function index()
    {
        return view('Admin.index');
    }

    /**
     * 个人中心
     * @desc 个人中心
     */
    public function profile(Request $request)
    {
        if (!$request->ajax()) {
            return view('Admin.profile');
        }
    }
}
