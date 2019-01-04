<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use OSS\OssClient;

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
        return Auth::user()->getFiles($request->get('pid', 0), $request->get('per_page', 50));
    }

    /**
     * 创建目录
     * @desc 创建目录
     */
    public function createFolder(Request $request)
    {
        return Auth::user()->createFolder($request->get('pid', 0), $request->get('name'));
    }

    /**
     * 删除文件
     * @desc 删除文件或目录
     */
    public function deleteFiles(Request $request)
    {
        return Auth::user()->deleteFiles($request->get('ids'));
    }

    /**
     * 检查文件唯一
     * @desc 确保文件唯一性
     */
    public function checkFile(Request $request)
    {
        return Auth::user()->checkFile($request->get('path'));
    }

    /**
     * 获取上传策略
     * @desc 获取OSS上传策略
     */
    public function getPolicy()
    {
        return Auth::user()->getPolicy();
    }

    public function test()
    {

//        return Auth::user()->gateway('qiniu')->getPolicy();
//        $file = '../public/images/faces/avatar2.jpg';

//        dd(Storage::disk('oss')->putFileAs('avatars', new File($file), 'avatar3.jpg'));
//        dd(Storage::disk('qiniu')->put('avatars/avatar1.jpg', file_get_contents($file)));

//        dd(Storage::disk('qiniu')->prepend('file.log', 'Prepended Text'));
//        dd(Storage::disk('qiniu')->append('file.log', 'Appended Text'));

//        dd(Storage::disk('qiniu')->copy('avatars/avatar1.jpg', 'faces/avatar1.jpg'));
//        dd(Storage::disk('qiniu')->move('avatars/avatar3.jpg', 'avatars/avatar5.jpg'));

//        dd($visibility = Storage::disk('qiniu')->getVisibility('avatars/avatar5.jpg'));
//        dd($visibility = Storage::disk('qiniu')->setVisibility('avatars/avatar5.jpg', 'public'));

//        dd(Storage::disk('qiniu')->delete('avatars/avatar1.jpg'));
//        dd(Storage::disk('qiniu')->delete(['avatars/avatar5.jpg', 'faces/avatar1.jpg']));

//        dd(Storage::disk('qiniu')->files('/'));
//        dd(Storage::disk('qiniu')->allFiles('/'));
//        dd(Storage::disk('qiniu')->directories('/'));
//        dd(Storage::disk('qiniu')->allDirectories('/'));

//        dd(Storage::disk('qiniu')->files('/'));todo
//        dd(Storage::disk('qiniu')->allFiles('/'));todo
//        dd(Storage::disk('qiniu')->directories('/'));todo
//        dd(Storage::disk('qiniu')->allDirectories('/'));todo

//        dd(Storage::disk('oss')->makeDirectory('test'));
//        dd(Storage::disk('oss')->deleteDirectory('test'));

//        dd(Storage::disk('qiniu')->exists('file.log'));

//        dd(Storage::disk('qiniu')->get('file.log'));

//        dd(Storage::disk('qiniu')->url('Pairs.jpg'));

//        dd($size = Storage::disk('qiniu')->size('test/Paris.jpg'));
//        dd($size = Storage::disk('qiniu')->lastModified('test/Paris.jpg'));

//        dd(Storage::disk('qiniu')->exists('test/Paris.jpg'));
    }
}
