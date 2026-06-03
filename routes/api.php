<?php

use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileAttendanceController;
use App\Http\Controllers\Api\MobileStudentController;
use App\Http\Controllers\Api\MobileTeacherController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('mobile')->group(function () {
    Route::post('/login/student', [MobileAuthController::class, 'loginStudent']);
    Route::post('/login/teacher', [MobileAuthController::class, 'loginTeacher']);
    Route::post('/register', [MobileAuthController::class, 'registerDevice']);
    Route::post('/attendance', [MobileAttendanceController::class, 'tap'])->middleware('nfc.api.key');

    Route::get('/student/profile', [MobileStudentController::class, 'profile']);
    Route::get('/student/summary', [MobileStudentController::class, 'summary']);
    Route::get('/student/absensi', [MobileStudentController::class, 'absensi']);
    Route::get('/student/leave-requests', [MobileStudentController::class, 'leaveRequests']);
    Route::post('/student/leave-requests', [MobileStudentController::class, 'storeLeaveRequest']);
    Route::get('/teacher/absences', [MobileTeacherController::class, 'absences']);
});

// NFC Monitoring API
Route::get('/monitoring/nfc-data', [App\Http\Controllers\MonitoringController::class, 'nfcData'])->middleware('nfc.api.key');
