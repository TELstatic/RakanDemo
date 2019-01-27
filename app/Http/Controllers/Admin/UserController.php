<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * 用户管理
 * @desc 用户管理
 */
class UserController extends Controller
{
    /**
     * 用户一览
     * @desc 获取用户列表
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('Admin.user.index');
        }

        $status = $request->get('status', 'all');

        $where = [];

        switch ($status) {
            default:
            case 'all':
                break;
            case 'admin':
                $where[] = [
                    'is_admin', 'yes',
                ];
                break;
            case 'client':
                $where[] = [
                    'is_admin', 'no',
                ];
                break;
        }

        $builder = User::query();

        if ($keyword = $request->get('keyword')) {
            $like = '%'.$keyword.'%';

            $builder->where(function ($query) use ($like) {
                $query->where('name', 'like', $like)
                    ->orWhere('phone', 'like', $like);
            });
        }

        $users = $builder->where($where)->with('roles')->paginate($request->get('per_page'));

        return $users;
    }

    /**
     * 角色类型
     * @desc 获取角色类型
     */
    public function role()
    {
        $select = [
            'name as value',
            'name as label',
        ];

        $roles = Role::select($select)->get();
        return $roles;
    }

    /**
     * 权限获取
     * @desc 获取用户权限
     */
    public function permission()
    {
        $permissions = Auth::guard('admin')->user()->getAllPermissions();

        return $permissions->map(function (Permission $permission) {
            return $permission->name;
        });
    }

    /**
     * 禁用用户
     * @desc 启用|禁用用户,启用即为平台工作人员,否则为游客
     */
    public function active(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->is_admin = $request->get('status', 'no');

        $bool = $user->save();

        if ($request->get('status', 'no') == 'no') {
            return reply($bool, $bool ? '用户禁用成功' : '用户禁用失败');
        } else {
            return reply($bool, $bool ? '用户启用成功' : '用户启用失败');
        }
    }

    /**
     * 更新用户
     * @desc 更新用户
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $bool = $user->syncRoles($request->get('role'));

        return reply($bool, $bool ? '用户更新成功' : '用用户更新失败');
    }
}
