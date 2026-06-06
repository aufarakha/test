<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\KompetensiController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// User Management
Route::get('/user/{std_code}', [UserController::class, 'show']);
Route::put('/user/{std_code}', [UserController::class, 'update']);

// Exam
Route::post('/exam/start', [ExamController::class, 'start']);
Route::post('/exam/submit', [ExamController::class, 'submit']);
Route::post('/exam/unlock-review', [ExamController::class, 'unlockReview']);
Route::get('/exam/questions', [ExamController::class, 'getQuestions']);

// Kompetensi Keahlian
Route::get('/kompetensi-keahlian', [KompetensiController::class, 'index']);
