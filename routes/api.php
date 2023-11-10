<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;


Route::get('/error',function(){
    return response()->json(['msg'=>'unauthorized']);
});

//userController
Route::post('/user', [UserController::class,'store']);
Route::post('/user/verify', [UserController::class,'verify']);
Route::post('/user/update', [UserController::class,'update']);
Route::get('/user/code/{phone}', [UserController::class,'code']);
Route::post('/user/google/callback', [UserController::class,'google']);
Route::post('/user/data/add', [UserController::class,'data']);


//workerController
Route::post('/worker', [WorkerController::class,'store']);
Route::post('/worker/verify', [WorkerController::class,'verify']);
Route::post('/worker/update', [WorkerController::class,'update']);
Route::get('/worker/code/{phone}', [WorkerController::class,'code']);
Route::post('/worker/data/add', [WorkerController::class,'data']);

//jobController
Route::post('/jobs', [JobController::class,'index']);
Route::post('/job/add', [JobController::class,'add']);
Route::post('/job/select', [JobController::class,'select']);
Route::post('/jobs/some', [JobController::class,'get_jobs']);
Route::post('/job/comments', [JobController::class,'get_job_comments']);
Route::post('/user/jobs', [JobController::class,'get_user_jobs']);

//commentController
Route::post('/comment/add', [CommentController::class,'add']);

//chatController



//paymentController


//evaluationController
Route::post('/assess',[EvaluationController::class,'index']);
Route::post('/assess/add',[EvaluationController::class,'assess']);
Route::post('/assess/some',[EvaluationController::class,'get_evaluations']);
