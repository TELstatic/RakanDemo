<?php

namespace App\Providers;

use App\Service\MenuService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Elasticsearch\ClientBuilder as ESClientBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if (php_sapi_name() !== 'cli') {
            $this->buildMenu();
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('es', function () {
            $builder = ESClientBuilder::create()->setHosts(config('elasticquent.config.hosts'));
            return $builder->build();
        });
    }


    protected function buildMenu()
    {
        $key = config('app.name').'_menus';

        Cache::forget($key);
//
        $value = Cache::rememberForever($key, function () {
            $menu = new MenuService();

            return $menu->build();
        });

        View::share('AdminMenu', $value);
    }
}
