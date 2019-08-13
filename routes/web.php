<?php

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

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

// 用户注册
Route::get('/index', 'Test\RegController@index');
Route::post('/reg', 'Test\RegController@reg');



/**            购物车                                    */
// 添加
Route::get('/cart/add', 'Controller\CartController@addCart');
// 删除
Route::get('/cart/del', 'Controller\CartController@delCart');
// 数量修改
Route::get('/cart/upd', 'Controller\CartController@updCart');
// 数据展示
Route::get('/cart/show', 'Controller\CartController@show');

/**            订单                                       */
// 订单生成
Route::get('/order/create', 'Controller\OrderController@create');
Route::get('/order/all', 'Controller\OrderController@store');


/**            支付                                       */
// 支付
Route::get('/pay/alipay', 'Controller\alipayController@alipay');
// 同步通知
Route::get('/pay/success', 'Controller\alipayController@success');
// 异步通知
Route::post('/pay/notify', 'Controller\alipayController@notify');
