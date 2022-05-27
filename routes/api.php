<?php

use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\UserController;
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

Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'advertisement'], function () {
    Route::get('/', [AdvertisementController::class, 'list']);
    Route::get('show/{id}', [AdvertisementController::class, 'show']);
});

Route::group(['prefix' => 'advertisement', 'middleware' => 'auth:sanctum'], function () {
    Route::post('add', [AdvertisementController::class, 'store']);
    Route::get('edit/{id}', [AdvertisementController::class, 'edit']);
    Route::put('update/{id}', [AdvertisementController::class, 'update']);
    Route::delete('delete/{id}', [AdvertisementController::class, 'destroy']);
});
