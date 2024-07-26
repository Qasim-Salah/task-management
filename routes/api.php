<?php

use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\API\V1\HomeController;
use App\Http\Controllers\API\V1\Project\ProjectController;
use App\Http\Controllers\API\V1\Task\TaskController;
use App\Http\Controllers\API\V1\User\ProfileController;
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

Route::middleware(['throttle:60,1'])->prefix('v1')->group(function () {

//==============================auth============================
    Route::prefix('/auth')->controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::put('password/change/', 'changePassword');
        Route::post('logout', 'logout')->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->group(function () {
        //==============================projects============================
        Route::prefix('/projects')->controller(ProjectController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::post('/{id}', 'update');
            Route::get('/{id}', 'show');
            Route::delete('/{id}/delete', 'destroy');
        });

        //==============================tasks============================
        Route::prefix('/tasks')->controller(TaskController::class)->group(function () {
            Route::get('/{projectId}', 'index');
            Route::post('/', 'store');
            Route::post('/{id}', 'update');
            Route::get('/show/{id}/{projectId}', 'show');

            // Creat comment by task
            Route::post('/comment/{task_id}', 'storeComment');
            Route::delete('/delete/{id}/{projectId}', 'destroy');
        });

        //==============================profile============================
        Route::prefix('/profile')->controller(ProfileController::class)->group(function () {
            Route::get('/', 'profile');
            Route::post('/update', 'update');
        });

        Route::redirect('/', '/home');
        Route::get('/home', HomeController::class);
    });
});

