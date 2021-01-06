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

Route::resource('warehouses', WarehouseController::class);
Route::resource('providers', ProviderController::class);
Route::resource('users', UserController::class);
Route::resource('goods', GoodsController::class);
Route::resource('storages', StorageController::class);
Route::resource('storage_ins', StorageInController::class);
Route::resource('storage_outs', StorageOutController::class);

Route::get('users/{user}/storages', 'UserController@showStorageLogs');
Route::get('providers/{provider}/goods', 'ProviderController@showGoods');
Route::get('warehouses/{warehouse}/storages', 'WarehouseController@showStorages');

