<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

Route::middleware(['auth', 'verified'])->group(function (){
    
    // ******** 一般ユーザー ********
    //  勤怠登録
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.index');

    // 勤怠一覧
    Route::get('/attendance/list', [AttendanceController::class, 'index'])->name('attendance.index');

    // 勤怠詳細
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'show'])->name('attendance.show');

    // 申請一覧画面 
    Route::get('stamp_correction_request/list', [RequestController::class, 'index'])->name('request.index');




    // ******** 管理者 ********
    // 勤怠一覧
    Route::get('/admin/attendance/list', [AttendanceController::class, 'index'])->name('attendance.index');

    // 勤怠詳細
    Route::get('/admin/attendance/detail/{id}', [AttendanceController::class, 'show'])->name('attendance.show');

    // スタッフ一覧
    Route::get('/admin/staff/list', [AttendanceController::class, 'show'])->name('attendance.show');

    // スタッフ別勤怠一覧
    Route::get('/admin/attendance/staff/{id}', [AttendanceController::class, 'show'])->name('attendance.show');

    // 申請一覧画面 
    Route::get('stamp_correction_request/list', [RequestController::class, 'index'])->name('request.index');

    // 修正申請承認画面 
    Route::put('stamp_correction_request/approvr/{attendance_correct_request_id}', [RequestController::class, 'index'])->name('request.index');


});
