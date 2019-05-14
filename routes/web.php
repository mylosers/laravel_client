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

Route::post('/base','Base\BaseController@base'); //执行加密并发送到api 后台解析
Route::get('/baseList','Base\BaseController@baseList');
Route::get('/baseNo','Base\BaseController@baseNo');  //执行解密

Route::get('/Caesar','Base\CaesarController@base');  //加密解密
Route::get('/on','Base\CaesarController@on');  //测试加密-2
Route::get('/off','Base\BaseController@off');  //测试解密-2
Route::get('/rsa','Base\BaseController@rsa');  //测试解密-2
Route::get('/sign','Base\BaseController@sign');  //测试解密-3

Route::get('/request','Login\RequestController@request');  //注册页面
Route::post('/requestAdd','Login\RequestController@requestAdd');  //注册执行
Route::get('/login','Login\LoginController@Login');  //登陆页面
Route::post('/loginAdd','Login\LoginController@loginAdd');  //登陆执行
