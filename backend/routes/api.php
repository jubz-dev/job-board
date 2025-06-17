<?php

use App\Http\Controllers\Api\JobPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/jobPosts', [JobPostController::class, 'store']);
Route::get('/jobPosts', [JobPostController::class, 'index']);
