<?php

use App\Collections\Collection;
use Illuminate\Support\Facades\Route;
use App\Database\InsertBuilder;
use App\Exceptions\ValidationException;
use App\User\User;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user', [UserController::class, 'index']);

Route::post('/user/sanitize', [UserController::class, 'sanitize']);