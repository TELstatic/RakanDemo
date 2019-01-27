<?php

namespace App\Listeners;

use App\Events\RebuildMenu;
use App\Models\Menu;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RebuildMenuListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RebuildMenu $event
     * @return void
     */
    public function handle(RebuildMenu $event)
    {
        $permissions = $event->role->getAllPermissions()->pluck('name')->toArray();

        $menus = Menu::pluck('url')->toArray();

        $permissions = array_map(function ($item) {
            return '/' . $item;
        }, $permissions);

        $result = array_intersect($permissions, $menus);

        $ids = [];

        foreach ($result as $item) {
            $ids[] = Menu::where('url', $item)->value('id');
        }

        $pid = Menu::whereIn('id', $ids)->whereNotNull('pid')->pluck('pid')->toArray();

        $menuIds = array_sort(array_unique(array_merge($ids, $pid)));

        $data = [];

        foreach ($menuIds as $id) {
            $data[] = [
                'role_id' => $event->role->id,
                'menu_id' => $id
            ];
        }

        if (!empty($data)) {
            DB::table('role_has_menus')->where('role_id', $event->role->id)->delete();
            DB::table('role_has_menus')->insert($data);

            $key = 'product_menus';

            Cache::forget($key);
        }
    }
}
