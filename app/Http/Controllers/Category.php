<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Category extends Controller
{
    protected $category;
    protected $from;
    protected $key;
    protected $id;
    protected $flag;

    /**
     * $category 分类模型
     * $flag 是否来自店铺请求
     */
    public function __construct($category, $from = false, $key = null)
    {
        $this->category = $category;
        $this->from = $from;
        $this->key = $key;

        if (!$from) {
            $this->id = 0;
        }
    }

    /**
     * 选择数据
     */
    public function get()
    {
        $select = [
            'name as title',
            'name as label',
            'id as value',
            'name',
            'sort',
            'id as attr',
            'id',
            'parent_id',
        ];

        return $select;
    }

    /**
     * 存储数据
     */
    public function data(Request $request)
    {
        $data = [
            'name'         => $request->get('name'),
            'sort'         => $request->get('sort', 0),
            'is_directory' => false
        ];

        return $data;
    }

    /**
     * 白名单
     */
    protected function white()
    {
        return [];
    }

    /**
     * 黑名单
     */
    public function black()
    {
        return [];
    }

    /**
     * 获取根目录
     */
    public function root()
    {
        $where = [];

        $where[] = [
            'parent_id', null
        ];

        return $this->query($where, $this->get());
    }

    /**
     * 查询数据
     */
    public function query($where, $select)
    {
        if ($this->from) {
            $white = $this->white();

            return $this->category->where($where)->whereIn('id', $white)->select($select)->get();
        }
        return $this->category->where($where)->select($select)->get();
    }

    /**
     * 递归
     */
    public function recursive($pid, $flag = true)
    {
        $arr[] = [];
        $where['parent_id'] = $pid;

        if ($arr = $this->query($where, $this->get())) {
            foreach ($arr as $k => $v) {
                $arr[$k]['children'] = $this->recursive($v->id, $flag);
            }
        }

        return $arr;
    }

    /**
     * 获取分类下拉选择
     */
    public function select($seperator = "　")
    {
        return Cache::rememberForever($this->key(__FUNCTION__), function () use ($seperator) {
            $arr = $this->root();
            if ($arr) {
                foreach ($arr as $k => $v) {
                    $arr[$k]['children'] = $this->recursive($v->id);
                }
            }

            $select = [];

            $k = 0;

            $select[$k]['label'] = 'Root';
            $select[$k]['value'] = 0;

            $k++;

            for ($i = 0; $i < count($arr); $i++) {
                $select[$k]['label'] = str_repeat($seperator, 1).$arr[$i]['title'];
                $select[$k]['value'] = $arr[$i]['id'];
                $k++;
            }

            return $select;
        });
    }

    /**
     * 获取分类树
     */
    public function tree()
    {
        return Cache::rememberForever($this->key(__FUNCTION__), function () {
            $arr = $this->root();

            if ($arr) {
                foreach ($arr as $k => $v) {
                    $arr[$k]['children'] = $this->recursive($v->id);
                }
            }

            return $arr;
        });
    }

    /**
     * 添加分类
     */
    public function store(Request $request)
    {
        $data = $this->data($request);

        $category = new $this->category($data);

        $parent = null;

        if (isset($request->pid) && $request->pid != 0) {
            $parent = $this->category->findOrFail($request->pid);

            $parent->is_directory = true;

            $parent->save();
        }
        if (!is_null($parent)) {
            $category->parent()->associate($parent);
        }

        $bool = $category->save();

        $this->clear();
        return responseCode($bool ? 200 : 500);
    }

    /**
     * 更新分类
     */
    public function update(Request $request, $id)
    {
        $data = $this->data($request);

        unset($data['is_directory']);

        $where = [
            'id' => $id
        ];

        $bool = $this->category->where($where)->update($data);

        $this->clear();
        return responseCode($bool ? 200 : 500);
    }

    /**
     * 删除分类
     */
    public function batch(Request $request)
    {
        $bool = $this->category->destroy($request->ids);
        $this->clear();
        return responseCode($bool ? 200 : 500);
    }

    /**
     * 键名
     */
    public function key($fun)
    {
        if ($this->from) {
            $this->id = 1;
        }

        return $this->key.':'.$this->id.':'.$fun;
    }

    /**
     * 顶级分类
     */
    public function top()
    {
        $select = [
            'name as label',
            'id as value'
        ];

        if ($this->from) {
            $where = [];

            $white = $this->white();

            return Cache::rememberForever($this->key(__FUNCTION__), function () use ($select, $where, $white) {
                return $this->category->where('level', 0)->where($where)->whereIn('id', $white)->select($select)->get();
            });
        }

        return Cache::rememberForever($this->key(__FUNCTION__), function () use ($select) {
            return $this->category->where('level', 0)->select($select)->get();
        });
    }

    /**
     * 清空缓存
     */
    public function clear()
    {
        Cache::forget($this->key('tree'));
        Cache::forget($this->key('select'));
        Cache::forget($this->key('top'));
    }
}
