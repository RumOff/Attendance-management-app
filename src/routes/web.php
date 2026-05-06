<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;


// Route::middleware(['auth'])->group(function (){

    // ******** 一般ユーザー ********
    //  勤怠登録
    Route::get('/attendance', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/attendance', [StaffController::class, 'store'])->name('staff.store');

    // 勤怠一覧
    Route::get('/attendance/list', [StaffController::class, 'history'])->name('staff.history');

    // 勤怠詳細
    Route::get('/attendance/detail/{id}', [StaffController::class, 'show'])->name('staff.show');



    // ******** 管理者 ********
    // 勤怠一覧
    Route::get('/admin/attendance/list', [AdminController::class, 'history'])->name('admin.history');

    // 勤怠詳細
    Route::get('/admin/attendance/detail/{id}', [AdminController::class, 'show'])->name('admin.show');

    // スタッフ一覧
    Route::get('/admin/staff/list', [AdminController::class, 'staffList'])->name('admin.staffList');

    // スタッフ別勤怠一覧
    Route::get('/admin/attendance/staff/{id}', [AdminController::class, 'staffAttendance'])->name('admin.staffAttendance');

    // 修正申請承認画面
    Route::get('stamp_correction_request/approve/{attendance_correct_request_id}', [AdminController::class, 'approve'])->name('admin.approve');



    // ******** 共通 ********
    // 申請一覧画面
    Route::get('stamp_correction_request/list', [AdminController::class, 'requests'])->name('admin.requests');

    Route::post('stamp_correction_request/list', [StaffController::class, 'storeRequests'])->name('staff.storeRequests');

// });
