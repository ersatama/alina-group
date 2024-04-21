<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {

    /*** AUTHORIZATION ***/
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    /*** TASKS ***/
    Route::prefix('task')->group(function() {

        Route::post('get', [TaskController::class, 'get']);
        Route::get('getById/{id}', [TaskController::class, 'getById']);
        Route::delete('delete/{id}', [TaskController::class, 'delete']);
        Route::put('update/{id}', [TaskController::class, 'update']);
        Route::post('create', [TaskController::class, 'create']);

    });
});
