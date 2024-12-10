<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\Auth\PhoneDniLoginController;

Route::post('/login-phone-dni', [PhoneDniLoginController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getUserData']);
Route::middleware('auth:sanctum')->post('/register-paciente', [UserController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);