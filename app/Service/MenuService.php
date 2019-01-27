<?php

namespace App\Service;

use App\Models\Menu as UserMenu;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Menu\Html;
use Spatie\Menu\Link;
use Spatie\Menu\Menu;

class MenuService
{
    public function build()
    {
        $roles = Role::get(['id', 'name']);

        $menu = [];

        foreach ($roles as $role) {
            $menu[$role->name] = Menu::new()->add(Html::raw('菜单')->addParentClass('header'))->addClass('sidebar-menu')->setAttributes(['data-widget' => 'tree']);

            $menuIds = DB::table('role_has_menus')->where('role_id', $role->id)->pluck('menu_id')->toArray();


            if ($role->name == '超级管理员' || $role->id == 1) {
                $menus = UserMenu::with('child')->whereNull('pid')->get()->toArray();
            } else {
                $menus = UserMenu::with('child')->whereHas('child')->whereIn('id', $menuIds)->get()->toArray();
            }

            foreach ($menus as $key => $value) {
                if ($value['child'] != null) {
                    $child = Menu::new()->addClass('treeview-menu');

                    for ($i = 0; $i < count($value['child']); $i++) {
                        $link = Link::to($value['child'][$i]['url'], $value['child'][$i]['name'])
                            ->setAttribute('target', $value['child'][$i]['type']);
                        $child->add($link);
                    }

                    $child->addParentClass('treeview');

                    if ($value['icon'] != null) {
                        $icon = "<i class='".$value['icon']."'></i>";
                    } else {
                        $icon = '';
                    }

                    $text = "<span>".$value['name']."</span>";

                    $btn = '<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>';

                    $menu[$role->name]->submenu(Link::to('#', $icon.$text.$btn), $child);
                } else {
                    if ($value['icon'] != null) {
                        $icon = "<i class='".$value['icon']."'></i>";
                    } else {
                        $icon = '';
                    }
                    $text = "<span>".$value['name']."</span>";

                    $link = Link::to($value['url'], $icon.$text)
                        ->setAttribute('target', $value['type']);

                    $menu[$role->name]->add($link);
                }
            }
        }

        return $menu;
    }
}