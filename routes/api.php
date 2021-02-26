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

Route::get('/v1', 'App\Http\Controllers\APIController@index');
Route::get('/v1/list-area/{map_id}', 'App\Http\Controllers\APIController@getListArea');
Route::get('/v1/detail/{id}', 'App\Http\Controllers\APIController@show');
Route::post('/v1/add-area', 'App\Http\Controllers\APIController@createArea');
Route::put('/v1/update-area', 'App\Http\Controllers\APIController@updateArea');
Route::post('/v1/upload-image', 'App\Http\Controllers\APIController@store');
Route::delete('/v1/remove-area/{id_area}', 'App\Http\Controllers\APIController@removeArea');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
