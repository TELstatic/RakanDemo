<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Category;

/**
 * 分类管理
 * @desc 分类管理
 */
class CategoryController extends Category
{
    public function __construct(\App\Models\Category $category, bool $from = false, $key = null)
    {
        parent::__construct($category, $from, $key);
    }

    /**
     * 获取分类树
     * @desc 获取分类树
     */
    public function tree()
    {
        return parent::tree();
    }
}
