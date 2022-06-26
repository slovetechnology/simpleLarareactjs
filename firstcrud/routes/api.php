<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;


Route::get('/students', [StudentController::class, 'index']);
Route::post('/student/store', [StudentController::class, 'store']);
Route::get('/student/edit/{id}', [StudentController::class, 'edit']);
Route::post('/update_student/{id}', [StudentController::class, 'update_student']);
Route::delete('/delete_student/{id}', [StudentController::class, 'delete_student']);

Route::get('/blogs', [BlogController::class, 'index']);
Route::post('/blog/store', [BlogController::class, 'store']);
Route::delete('/blog/delete_blog/{id}', [BlogController::class, 'destroy']);
Route::get('/blog/edit_blog/{id}', [BlogController::class, 'edit']);

Route::post('/register', [RegisterController::class, 'create']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
