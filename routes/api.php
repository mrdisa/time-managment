<?php

use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProjectTimeController;
use App\Http\Controllers\ProjectUserController;
use App\Http\Controllers\UsersController;
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


// Login and Register
Route::post('/register', [UsersController::class, 'register']);
Route::post('/login', [UsersController::class, 'login']);






// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/logout', [UsersController::class, 'logout']);

    // Assign and Stats
    Route::post('/assign/{project_id}/{user_id}', [ProjectUserController::class, 'assign']);
    Route::get('/project/{project_id}/stats', [ProjectTimeController::class, 'stats']);

    // Projects Routes
    Route::prefix('projects')->group(function(){
        Route::get('/', [ProjectsController::class, 'index']);
        Route::get('/user', [ProjectUserController::class, 'list']);
        Route::post('/new', [ProjectsController::class, 'store']);
        Route::put('/{id}/finished', [ProjectsController::class, 'finished']);
        Route::post('/start-timer/{project_id}', [ProjectTimeController::class, 'start']);
        Route::post('/stop-timer/{project_id}', [ProjectTimeController::class, 'stop']);
    });
});




