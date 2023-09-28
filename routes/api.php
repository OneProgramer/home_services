<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;


Route::get('/error',function(){
    return response()->json(['msg'=>'unauthorized']);
});

Route::post('/user', [UserController::class,'store']);
Route::post('/user/verify', [UserController::class,'verify']);
Route::post('/user/update', [UserController::class,'update']);
Route::get('/user/code/{phone}', [UserController::class,'code']);
Route::post('/user/google/callback', [UserController::class,'google']);
// Route::get('/user/facebook/callback', [UserController::class,'code']);



Route::post('/worker', [WorkerController::class,'store']);
Route::post('/worker/verify', [WorkerController::class,'verify']);
Route::post('/worker/update', [WorkerController::class,'update']);
Route::get('/worker/code/{phone}', [WorkerController::class,'code']);


