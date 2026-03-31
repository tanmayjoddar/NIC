<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\ForgotPasswordController;
// Auth
Route::get('/signup',  [AuthController::class, 'signupPage']);
Route::post('/signup', [AuthController::class, 'signupSubmit']);
Route::get('/signin',  [AuthController::class, 'signinPage']);
Route::post('/signin', [AuthController::class, 'signinSubmit']);
Route::get('/logout',  [AuthController::class, 'logout']);

// CAPTCHA image (must be stateless GET, no CSRF)
Route::get('/captcha', [CaptchaController::class, 'generate']);

// OTP 2FA
Route::get('/otp',  [OtpController::class, 'showOtpPage']);
Route::post('/otp', [OtpController::class, 'verifyOtp']);

// Form (protected by session check inside controller)
Route::get('/form',    [FormController::class, 'index']);
Route::post('/submit', [FormController::class, 'submit']);
Route::get('/api/lgd/districts', [FormController::class, 'districts']);
Route::get('/api/lgd/subdistricts', [FormController::class, 'subdistricts']);
Route::get('/api/lgd/blocks', [FormController::class, 'blocks']);


// ── Forgot Password ────────────────────────────────
Route::get('/forget-password',  [ForgotPasswordController::class, 'showPage']);
Route::post('/forget-password', [ForgotPasswordController::class, 'submit']);

// ── Reset Password ─────────────────────────────────
Route::get('/reset-password',  [ForgotPasswordController::class, 'showResetPage']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
