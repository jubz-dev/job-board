<?php

use App\Http\Controllers\Api\JobPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/jobPosts', [JobPostController::class, 'store']);
Route::get('/jobPosts', [JobPostController::class, 'index']);

Route::get('/broadcast-config', function () {
    return response()->json([
        'key' => config('broadcasting.connections.pusher.key'),
        'cluster' => config('broadcasting.connections.pusher.options.cluster'),
        'host' => config('broadcasting.connections.pusher.options.host', null),
        'useTLS' => config('broadcasting.connections.pusher.options.useTLS', true),
    ]);
});
