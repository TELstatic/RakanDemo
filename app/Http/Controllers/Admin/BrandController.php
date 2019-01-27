<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BatchRequest;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 品牌管理
 * @desc 品牌管理
 */
class BrandController extends Controller
{
    /**
     * 品牌一览
     * @desc 获取品牌列表
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('Admin.brand.index');
        }

        $brands = Brand::paginate($request->get('per_page'));

        return $brands;
    }

    /**
     * 品牌选择
     * @desc 获取品牌下拉选择
     */
    public function select()
    {
        $select = [
            'title as label',
            'id as value',
        ];

        $brands = Brand::select($select)->get();

        return $brands;
    }

    /**
     * 添加品牌
     * @desc 添加品牌
     */
    public function store(BrandRequest $request)
    {
        $data = [
            'title'  => $request->get('title'),
            'sort'   => $request->get('sort', 0),
            'images' => $request->get('images')
        ];

        $bool = Brand::create($data);

        return reply($bool, $bool ? '品牌创建成功' : '品牌创建失败');
    }

    /**
     * 更新品牌
     * @desc 更新品牌
     */
    public function update(BrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $brand->title = $request->get('title');
        $brand->sort = $request->get('sort', 0);
        $brand->images = $request->get('images');

        $bool = $brand->save();

        return reply($bool, $bool ? '品牌更新成功' : '品牌更新失败');
    }

    /**
     * 删除品牌
     * @desc 批量删除品牌
     */
    public function batch(BatchRequest $request)
    {
        $bool = Brand::destroy($request->get('ids'));

        return reply($bool, $bool ? '品牌删除成功' : '品牌删除失败');
    }
}
