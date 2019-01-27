<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * 角色管理
 * @desc 站点角色管理
 */
class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:超级管理员'])->only(['index', 'store', 'update', 'destroy']);
    }

    /**
     * 角色列表
     * @desc 获取角色列表
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('Admin.role.index');
        }

        $roles = Role::with('permissions')->paginate($request->get('per_page'));

        return $roles;
    }

    /**
     * 添加角色
     * @desc 添加角色
     */
    public function store(Request $request)
    {
        $data = [
            'name' => $request->get('name'),
        ];

        $bool = DB::transaction(function () use ($data, $request) {
            $role = Role::create($data);
            $permissions = $request->get('permissions');
            $role->syncPermissions($permissions);

            $key = config('app.name').'menus';
            Cache::forget($key);

            return true;
        });

        return reply($bool, $bool ? '角色添加成功' : '角色添加失败');
    }

    /**
     * 更新角色
     * @desc 更新角色
     */
    public function update(Request $request, $id)
    {
        $role = Role::findById($id);

        $bool = DB::transaction(function () use ($role, $request) {
            $role->name = $request->get('name');
            $role->save();
            $permissions = $request->get('permissions');
            $role->syncPermissions($permissions);

            $key = config('app.name').'menus';
            Cache::forget($key);

            return true;
        });

        return reply($bool, $bool ? '角色更新成功' : '角色更新失败');
    }

    /**
     * 删除角色
     * @desc 删除非管理员角色
     */
    public function destroy($id)
    {
        $role = Role::findById($id);

        if ($role->id == 1 || $role->name == '超级管理员') {

            return reply(false, '角色删除失败');
        }

        $bool = Role::destroy($role->id);

        return reply($bool, $bool ? '角色删除成功' : '角色删除失败');
    }
}
