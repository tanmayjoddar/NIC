<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\AuthController;

Route::get('/signup',  [AuthController::class, 'signupPage']);
Route::post('/signup', [AuthController::class, 'signupSubmit']);
Route::get('/signin',  [AuthController::class, 'signinPage']);
Route::post('/signin', [AuthController::class, 'signinSubmit']);
Route::get('/logout',  [AuthController::class, 'logout']);
Route::get('/form',    [FormController::class, 'index']);
Route::post('/submit', [FormController::class, 'submit']);
