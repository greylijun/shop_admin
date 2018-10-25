<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// 在 "App\Http\Controllers\Api" 命名空间下的控制器
Route::namespace('Api')->group(function () {
	Route::prefix('phone')->group(function () {
		Route::get('/home/slider', 'HomeController@slider');
		Route::get('/home/type_scroll', 'HomeController@typeScroll');
		Route::get('/list/search', 'ListController@search');
		Route::get('/good/lists', 'GoodController@lists');
		Route::get('/good/{id}', 'GoodController@show');
	});
});

// 在 "App\Http\Controllers\Web" 命名空间下的控制器
Route::namespace('Web')->group(function () {
	Route::post('login', 'AuthController@login'); // 用户登录
	Route::post('logout', 'AuthController@logout');
	Route::group(['middleware' => 'auth.web'], function () {
		Route::get('type/lists', 'TypeController@lists');
		Route::get('good/image_list', 'GoodController@imageList');
		Route::delete('good', 'GoodController@delete');
		Route::apiResource('good', 'GoodController');
		Route::post('user/change_pwd', 'UserController@changePwd');
		Route::delete('image', 'ImageController@delete');
		Route::post('image/set_main', 'ImageController@setMain');
		Route::apiResource('image', 'ImageController');
		Route::post('upload/image', 'UploadController@image');
		Route::delete('type', 'TypeController@delete');
		Route::apiResource('type', 'TypeController');
		Route::get('home/type_pie', 'HomeController@typePie');
		Route::get('home/inventory_top_ten', 'HomeController@inventoryTopTen');
	});
});
