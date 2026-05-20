<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StampCorrectionRequestController;

// ******** 管理者ログイン ********
Route::get('/admin/login', [AdminController::class, 'showLoginForm']);
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/logout', [AdminController::class, 'logout']);

// ******** 一般ユーザー ********
Route::middleware(['auth:web'])->group(function (){
    //  勤怠登録
    Route::get('/attendance', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/attendance', [StaffController::class, 'store'])->name('staff.store');

    // 勤怠一覧
    Route::get('/attendance/list', [StaffController::class, 'history'])->name('staff.history');

    // 勤怠詳細
    Route::get('/attendance/detail/{id}', [StaffController::class, 'show'])->name('staff.show');

    // // 申請一覧
    // Route::get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'index'])->name('staff.requests');
    // Route::post('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'storeRequests'])->name('staff.storeRequests');
});


// ******** 管理者 ********
Route::middleware(['auth:admin'])->group(function () {
    // 勤怠一覧
    Route::get('admin/attendance/list', [AdminController::class, 'history'])->name('admin.history');

    // 勤怠詳細
    Route::get('admin/attendance/{id}', [AdminController::class, 'show'])->name('admin.show');

    // スタッフ一覧
    Route::get('admin/staff/list', [AdminController::class, 'staffList'])->name('admin.staffList');

    // スタッフ別勤怠一覧
    Route::get('admin/attendance/staff/{id}', [AdminController::class, 'staffAttendance'])->name('admin.staffAttendance');

    // 申請一覧
    // Route::get('stamp_correction_request/list', [StampCorrectionRequestController::class, 'index'])->name('admin.requests');
    // Route::post('stamp_correction_request/list', [StampCorrectionRequestController::class, 'storeRequests'])->name('admin.storeRequests');
    Route::get('stamp_correction_request/approve/{attendance_correct_request_id}', [StampCorrectionRequestController::class, 'approve'])->name('admin.approve');

});

// 申請一覧
    Route::get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'index'])->name('requests.index');
    Route::get('/stamp_correction_request/list/{status?}',[StampCorrectionRequestController::class, 'index']);

    Route::post('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'storeRequests'])->name('requests.storeRequests');