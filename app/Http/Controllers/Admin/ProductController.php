<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BatchRequest;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 商品管理
 * @desc 商品管理
 */
class ProductController extends Controller
{
    /**
     * 商品列表
     * @desc 获取商品列表
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('Admin.product.index');
        }

        $builder = Product::query();

        // 标题 SPU SKU 筛选
        if ($keyword = $request->get('keyword')) {
            $like = '%'.$keyword.'%';

            $builder->where(function ($query) use ($like) {
                $query->where('title', 'like', $like)
                    ->orWhere('spu', 'like', $like)
                    ->orWhereHas('items', function ($query) use ($like) {
                        $query->where('sku', 'like', $like);
                    });
            });
        }

        //品牌 筛选
        if ($brand = $request->get('brand')) {
            $builder->where('brand', $brand);
        }

        //分类 筛选
        if ($categories = $request->get('categories')) {
            $builder->whereRaw('categories->"$[2]" = ?', (int) end($categories));
        }

        $products = $builder->with('items')->paginate($request->get('per_page'));

        return $products;
    }

    /**
     * 创建商品
     * @desc 添加商品页面
     */
    public function create()
    {
        return view('Admin.product.create');
    }

    /**
     * 编辑商品
     * @desc 更新商品页面
     */
    public function edit($id)
    {
        $product = Product::with('items')->findOrFail($id);

        return view('Admin.product.edit')->with('product', $product);
    }

    /**
     * 添加商品
     * @desc 添加商品接口
     */
    public function store(ProductRequest $request)
    {
        $bool = DB::transaction(function () use ($request) {
            $data = [
                'title'           => $request->get('title'),
                'subtitle'        => $request->get('subtitle'),
                'if_open'         => $request->get('if_open', 0),
                'brand'           => $request->get('brand'),
                'brand_name'      => $request->get('brand_name'),
                'categories'      => $request->get('categories'),
                'categories_name' => $request->get('categories_name'),
                'images'          => $request->get('images'),
                'spu'             => $request->get('spu'),
                'price'           => $request->get('price'),
                'content'         => $request->get('content'),
            ];

            $product = Product::create($data);

            $items = [];

            foreach ($request->get('items') as $value) {
                $items[] = [
                    'category'      => end($request->get('categories')),
                    'category_name' => end($request->get('categories_name')),
                    'sku'           => $value['sku'],
                    'module'        => $value['module'],
                    'standard'      => $value['standard'],
                    'price'         => $value['price'],
                    'reserve'       => $value['reserve'],
                    'images'        => $value['images'],
                ];
            }

            $product->items()->createMany($items);

            return true;
        });

        return reply($bool, $bool ? '商品添加成功' : '商品添加失败');
    }

    /**
     * 更新商品
     * @desc 更新商品接口
     */
    public function update(ProductRequest $request, $id)
    {
        $bool = DB::transaction(function () use ($request, $id) {
            $product = Product::findOrFail($id);

            $product->title = $request->get('title');
            $product->subtitle = $request->get('subtitle');
            $product->if_open = $request->get('if_open', 0);
            $product->brand = $request->get('brand');
            $product->brand_name = $request->get('brand_name');
            $product->categories = $request->get('categories');
            $product->categories_name = $request->get('categories_name');
            $product->images = $request->get('images');
            $product->spu = $request->get('spu');
            $product->price = $request->get('price');
            $product->content = $request->get('content');

            $product->save();

            $items = [];
            $lists = [];

            foreach ($request->get('items') as $value) {
                //不存在 ID 添加
                if (!isset($value['id'])) {
                    $items[] = [
                        'category'      => end($request->get('categories')),
                        'category_name' => end($request->get('categories_name')),
                        'sku'           => $value['sku'],
                        'module'        => $value['module'],
                        'standard'      => $value['standard'],
                        'price'         => $value['price'],
                        'reserve'       => $value['reserve'],
                        'images'        => $value['images'],
                    ];
                } else {
                    $lists[] = [
                        'id'            => $value['id'],
                        'category'      => end($request->get('categories')),
                        'category_name' => end($request->get('categories_name')),
                        'sku'           => $value['sku'],
                        'module'        => $value['module'],
                        'standard'      => $value['standard'],
                        'price'         => $value['price'],
                        'reserve'       => $value['reserve'],
                        'images'        => $value['images'],
                    ];
                }
            }

            if (!empty($items)) {
                $product->items()->createMany($items);
            }

            ProductItem::updateBatch($lists);

            return true;
        });

        return reply($bool, $bool ? '商品更新成功' : '商品更新失败');
    }

    /**
     * 上架|下架商品
     * @desc 上下架商品
     */
    public function active(Request $request)
    {
        $data = [
            'if_open' => $request->get('status', 0)
        ];

        $bool = Product::whereIn('id', $request->get('ids'))->update($data);

        if ($request->get('status', 0)) {

            return reply($bool, $bool ? '商品上架成功' : '商品上架失败');
        } else {

            return reply($bool, $bool ? '商品下架成功' : '商品下架失败');
        }
    }

    /**
     * 修改单品
     * @desc 修改单品参数
     */
    public function item(ItemRequest $request)
    {
        $item = ProductItem::findOrFail($request->get('id'));

        $item->sku = $request->get('sku');
        $item->module = $request->get('module');
        $item->standard = $request->get('standard');
        $item->price = $request->get('price');
        $item->reserve = $request->get('reserve');

        $bool = $item->save();

        return reply($bool, $bool ? '单品更新成功' : '单品更新失败');
    }

    /**
     * 删除商品
     * @desc 批量删除商品
     */
    public function batch(BatchRequest $request)
    {
        $bool = Product::destroy($request->get('ids'));

        return reply($bool, $bool ? '商品删除成功' : '商品删除失败');
    }

}
