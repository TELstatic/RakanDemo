<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

/**
 * 文件管理
 * @desc 文件管理
 */
class FileController extends Controller
{

    /**
     * 获取文件
     * @desc 获取文件列表
     */
    public function getFiles(Request $request)
    {
        return Auth::user()->gateway('qiniu')->module('web')->getFiles($request->get('pid', 0), $request->get('per_page', 50));
    }

    /**
     * 创建目录
     * @desc 创建目录
     */
    public function createFolder(Request $request)
    {
        return Auth::user()->gateway('qiniu')->module('web')->createFolder($request->get('pid', 0), $request->get('name'));
    }

    /**
     * 删除文件
     * @desc 删除文件或目录
     */
    public function deleteFiles(Request $request)
    {
        return Auth::user()->gateway('qiniu')->module('web')->deleteFiles($request->get('ids'));
    }

    /**
     * 检查文件唯一
     * @desc 确保文件唯一性
     */
    public function checkFile(Request $request)
    {
        return Auth::user()->gateway('qiniu')->module('web')->checkFile($request->get('path'));
    }

    /**
     * 获取上传策略
     * @desc 获取OSS上传策略
     */
    public function getPolicy()
    {
        return Auth::user()->gateway('qiniu')->getPolicy();
    }
}
