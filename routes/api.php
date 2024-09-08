<?php

use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\StudentPerformanceController;
use App\Http\Controllers\Teacher\HomeworkController;
use App\Http\Controllers\Teacher\MarksController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// Login route

Route::post('/login', [AuthController::class, 'login']);

// Logout route, protected by JWT auth middleware

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

// admin routes are

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::apiResource('teachers', TeacherController::class);
    Route::apiResource('students', StudentController::class);
});


Route::middleware(['auth:api', 'role:teacher'])->group(function () {
    Route::resource('teacher-students', TeacherStudentController::class);
    Route::resource('homeworks', HomeworkController::class)->only(['store', 'update']);
    Route::resource('marks', MarksController::class)->except(['destroy']);
});

Route::middleware(['auth:api', 'role:student'])->group(function () {
    Route::get('homeworks/{id}', [StudentPerformanceController::class, 'viewHomework']);
    Route::post('homeworks/submit', [StudentPerformanceController::class, 'submitHomework']);
    Route::get('performance', [StudentPerformanceController::class, 'monitorPerformance']);
});
