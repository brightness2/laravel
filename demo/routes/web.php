<?php


use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/**路由使用 */
Route::get('/hello', function () {
    return 'hello Brightness';
});

// 控制器多个方法共用一个路由
Route::any('Test/{action}', function (App\Http\Controllers\TestController $index, $action) {
    return $index->$action();
});

Route::any('db/{action}', function (App\Http\Controllers\DBController $index, $action) {
    return $index->$action();
});
