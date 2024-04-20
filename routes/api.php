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
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    Route::group(['middleware' => 'auth:api'], function() {

        /*** TASKS ***/
        Route::prefix('task')->group(function() {

            Route::get('get', [TaskController::class, 'get'])->name('task.get');
            Route::get('getById/{id}', [TaskController::class, 'getById'])->name('task.getById');
            Route::delete('deleteById/{id}', [TaskController::class, 'deleteById'])->name('task.delete');
            Route::put('update/{id}', [TaskController::class, 'update'])->name('task.update');
            Route::post('create', [TaskController::class, 'create'])->name('task.create');

        });

    });
});
