<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\PermissionService;

/**
 * 权限管理
 * @desc 权限管理
 */
class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:超级管理员'])->only(['index']);
    }

    /**
     * 权限列表
     * @desc 获取权限列表
     */
    public function index()
    {
        $permissionService = new PermissionService();

        return $permissionService->index();
    }


}
