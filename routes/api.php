<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;





// Route::post('logout', [AuthController::class,'logout']);
// Route::post('refresh', [AuthController::class,'refresh']);
// Route::post('me', [AuthController::class,'me']);

Route::get('/code/{phone}', [WorkerController::class,'code']);


Route::post('/user_login', [AuthController::class,'login']);
Route::post('/user_register', [UserController::class,'store']);
Route::post('/worker_register', [WorkerController::class,'store']);
Route::post('/worker_verify', [WorkerController::class,'verify']);
Route::post('/worker_login', [WorkerController::class,'login']);
