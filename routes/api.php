<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
$apiSpace = '\\App\Api\Controllers\\'; 
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('test', $apiSpace.'TestController@index'); 
Route::match(['get', 'post'],'user/login', $apiSpace.'UserController@login'); 
Route::match(['get', 'post'],'user/getuser', $apiSpace.'UserController@getUser'); 
Route::match(['get', 'post'],'system/menuList', $apiSpace.'SystemController@menuList'); 
Route::match(['get', 'post'],'system/addMenu', $apiSpace.'SystemController@addMenu'); 
Route::match(['get', 'post'],'system/delMenu', $apiSpace.'SystemController@delMenu'); 
Route::match(['get', 'post'],'system/batchDelMenu', $apiSpace.'SystemController@delMenu'); 
Route::match(['get', 'post'],'system/getMenu', $apiSpace.'SystemController@getMenu'); 
Route::match(['get', 'post'],'system/editMenu', $apiSpace.'SystemController@editMenu'); 
Route::match(['get', 'post'],'system/roleList', $apiSpace.'SystemController@roleList'); 
Route::match(['get', 'post'],'system/addRoles', $apiSpace.'SystemController@addRoles'); 
Route::match(['get', 'post'],'system/getRole', $apiSpace.'SystemController@getRole'); 
Route::match(['get', 'post'],'system/editRole', $apiSpace.'SystemController@editRole'); 
Route::match(['get', 'post'],'system/deleteRole', $apiSpace.'SystemController@deleteRole'); 
Route::match(['get', 'post'],'system/getRolePermission', $apiSpace.'SystemController@getRolePermission'); 
Route::match(['get', 'post'],'system/savePermission', $apiSpace.'SystemController@savePermission'); 
Route::match(['get', 'post'],'system/userList', $apiSpace.'SystemController@userList'); 
Route::match(['get', 'post'],'system/addUser', $apiSpace.'SystemController@addUser'); 
Route::match(['get', 'post'],'system/getUser', $apiSpace.'SystemController@getUser'); 
Route::match(['get', 'post'],'system/editUser', $apiSpace.'SystemController@editUser'); 
Route::match(['get', 'post'],'system/deleteUser', $apiSpace.'SystemController@deleteUser'); 
Route::match(['get', 'post'],'common/uploadImg', $apiSpace.'CommonController@uploadImg'); 
