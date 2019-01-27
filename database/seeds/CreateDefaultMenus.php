<?php

use Illuminate\Database\Seeder;

class CreateDefaultMenus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = $this->data();

        \Illuminate\Support\Facades\DB::statement('truncate menus');

        foreach ($menus as $item) {
            $data = [
                'icon' => $item['icon'],
                'url'  => $item['url'],
                'name' => $item['name'],
                'sort' => $item['sort'],
                'type' => $item['type']
            ];

            $menu = \App\Models\Menu::create($data);

            if (!empty($item['children'])) {
                foreach ($item['children'] as $child) {
                    $data = [
                        'icon' => $child['icon'],
                        'url'  => $child['url'],
                        'name' => $child['name'],
                        'sort' => $child['sort'],
                        'type' => $child['type'],
                        'pid'  => $menu->id
                    ];

                    \App\Models\Menu::create($data);
                }
            }
        }

        $key = config('app.name').'_menus';

        \Illuminate\Support\Facades\Cache::forget($key);
    }

    public function data()
    {
        return [
            [
                'icon' => 'fa fa-tachometer',
                'url'  => '/admin',
                'name' => '控制面板',
                'sort' => 0,
                'type' => '_self'
            ],
            [
                'icon'     => 'fa  fa-line-chart',
                'url'      => '#',
                'name'     => '商品管理',
                'sort'     => 0,
                'type'     => '_self',
                'children' => [
                    [
                        'icon' => '',
                        'url'  => '/admin/product/create',
                        'name' => '添加商品',
                        'sort' => 0,
                        'type' => '_self',
                    ],
                    [
                        'icon' => '',
                        'url'  => '/admin/product/index',
                        'name' => '商品一览',
                        'sort' => 0,
                        'type' => '_self',
                    ],
                ]
            ],
            [
                'icon'     => 'fa fa-users',
                'url'      => '#',
                'name'     => '参数管理',
                'sort'     => 0,
                'type'     => '_self',
                'children' => [
                    [
                        'icon' => 'fa fa-users',
                        'url'  => '/admin/brand/index',
                        'name' => '品牌一览',
                        'sort' => 0,
                        'type' => '_self',
                    ],
                ]
            ],
            [
                'icon' => 'fa fa-users',
                'url'  => '/admin/user/index',
                'name' => '用户管理',
                'sort' => 0,
                'type' => '_self',
            ],
            [
                'icon' => 'fa fa-ban',
                'url'  => '/admin/role/index',
                'name' => '角色管理',
                'sort' => 0,
                'type' => '_self',
            ],
        ];
    }
}
