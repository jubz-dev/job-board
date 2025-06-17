<?php

use App\Http\Controllers\ModeratorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/moderate/{jobPost}', [ModeratorController::class, 'handle'])->name('moderate');
