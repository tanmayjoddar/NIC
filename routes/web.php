<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;

Route::get('/form', [FormController::class, 'index']);
Route::post('/submit', [FormController::class, 'submit']);
Route::get('/signin', function() {
    return view('signin');
});
