<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'                 => 'bail|required',
            'subtitle'              => 'bail|required',
            'if_open'               => 'bail|required',
            'brand'                 => 'bail|required',
            'brand_name'            => 'bail|required',
            'categories'            => 'bail|required',
            'categories_name'       => 'bail|required',
            'images'                => 'bail|required',
            'spu'                   => 'bail|required',
            'price'                 => 'bail|required',
            'content'               => 'bail|required',
            'items.*.category'      => 'bail|required',
            'items.*.category_name' => 'bail|required',
            'items.*.sku'           => 'bail|required',
            'items.*.module'        => 'bail|required',
            'items.*.standard'      => 'bail|required',
            'items.*.price'         => 'bail|required',
            'items.*.reserve'       => 'bail|required',
            'items.*.images'        => 'bail|required',
        ];
    }

    public $attributes = [
        'title'                 => '商品标题',
        'subtitle'              => '商品副标题',
        'if_open'               => '上下架状态',
        'brand'                 => '品牌ID',
        'brand_name'            => '品牌名称',
        'categories'            => '分类',
        'categories_name'       => '分类名称',
        'images'                => '商品图片',
        'spu'                   => '商品SPU',
        'price'                 => '商品价格',
        'content'               => '商品详情',
        'items.*.category'      => '单品三级分类',
        'items.*.category_name' => '单品三级分类名称',
        'items.*.sku'           => '单品SKU',
        'items.*.module'        => '单品规格',
        'items.*.standard'      => '单品型号',
        'items.*.price'         => '单品价格',
        'items.*.reserve'       => '单品库存',
        'items.*.images'        => '单品封面',
    ];

}
