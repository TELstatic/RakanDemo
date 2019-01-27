<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['domain' => 'www'.config('session.domain')], function () {
    Route::group([
        'prefix'    => 'admin',
        'namespace' => 'Admin',
    ], function () {
        Route::get('login', 'LoginController@showLoginForm');
        Route::post('login', 'LoginController@login')->name('admin.login');
        Route::post('logout', 'LoginController@logout')->name('admin.logout');
    });
});

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {
    Route::prefix('file')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('test', 'Admin\FileController@test');
            Route::get('index', 'Admin\FileController@getFiles');
            Route::post('create', 'Admin\FileController@createFolder');
            Route::post('check', 'Admin\FileController@checkFile');
            Route::get('policy', 'Admin\FileController@getPolicy');
            Route::delete('batch', 'Admin\FileController@deleteFiles');
        });

        Route::prefix('web')->group(function () {
            Route::get('index', 'Web\FileController@getFiles');
            Route::post('create', 'Web\FileController@createFolder');
            Route::post('check', 'Web\FileController@checkFile');
            Route::get('policy', 'Web\FileController@getPolicy');
            Route::delete('batch', 'Web\FileController@deleteFiles');
        });

    });
});

//后台管理
Route::namespace('Admin')->prefix('admin')->middleware('auth.admin')->group(function () {
    Route::get('/', 'IndexController@index');
    Route::get('profile', 'IndexController@profile')->name('admin.profile');

    // 文件管理
    Route::prefix('file')->group(function () {
        Route::get('index', 'FileController@getFiles');
        Route::post('create', 'FileController@createFolder');
        Route::post('check', 'FileController@checkFile');
        Route::get('policy', 'FileController@getPolicy');
        Route::delete('batch', 'FileController@deleteFiles');
    });

    // 用户管理
    Route::prefix('user')->group(function () {
        Route::get('index', 'UserController@index');
        Route::get('role', 'UserController@role');
        Route::get('permission', 'UserController@permission');
        Route::post('active/{id}', 'UserController@active');
        Route::post('/', 'UserController@store');
        Route::put('update/{id}', 'UserController@update');
    });

    // 角色管理
    Route::prefix('role')->group(function () {
        Route::get('index', 'RoleController@index');
        Route::post('/', 'RoleController@store');
        Route::put('update/{id}', 'RoleController@update');
        Route::delete('{id}', 'RoleController@destroy');
    });

    //权限管理
    Route::prefix('permission')->group(function () {
        Route::get('index', 'PermissionController@index');
    });

    // 商品管理
    Route::prefix('product')->group(function () {
        Route::get('create', 'ProductController@create');
        Route::get('edit/{id}', 'ProductController@edit');
        Route::get('index', 'ProductController@index');
        Route::post('/', 'ProductController@store');
        Route::put('update/{id}', 'ProductController@update');
        Route::put('active', 'ProductController@active');
        Route::put('item', 'ProductController@item');
        Route::delete('batch', 'ProductController@batch');
    });

    //分类管理
    Route::prefix('category')->group(function () {
        Route::get('tree', 'CategoryController@tree');
    });

    // 品牌管理
    Route::prefix('brand')->group(function () {
        Route::get('index', 'BrandController@index');
        Route::get('select', 'BrandController@select');
        Route::post('/', 'BrandController@store');
        Route::put('update/{id}', 'BrandController@update');
        Route::delete('batch', 'BrandController@batch');
    });

});