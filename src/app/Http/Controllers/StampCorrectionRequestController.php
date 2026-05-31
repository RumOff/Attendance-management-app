<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\BreakTime;
use App\Http\Requests\AttendanceUpdateRequest;

class StampCorrectionRequestController extends Controller
{
    public function index($status = 'pending'){

        // ******** 管理者 ********
        if (Auth::guard('admin')->check()) {

            // 管理者用データ取得
            $requests = AttendanceRequest::with('user')
                ->where('status', $status)
                ->get();

            return view('admin.request', compact('requests','status'));
        }

        // ******** 一般ユーザー ********
        $requests = AttendanceRequest::with('attendance')
        ->where('user_id', auth()->id())
        ->with('user')
        ->where('status', $status)
        ->get();
        
        return view('admin.request', compact('requests','status'));

    }


    public function storeRequests(AttendanceUpdateRequest $request){
        
        if (Auth::guard('admin')->check()) {

            $attendance = AttendanceRecord::findOrFail(
                $request->attendance_id
            );

            $attendance->update([
                'clock_in' => $request->clock_in,
                'clock_out' => $request->clock_out,
                'remarks' => $request->remarks,
            ]);

            // 既存の休憩
            foreach ($attendance->breaks as $index => $break) {
                $break->update([
                    'break_start' => $request->break_start[$index],
                    'break_end' => $request->break_end[$index],
                ]);
            }

            // 新規の休憩
            $lastIndex = count($request->break_start) - 1;

            if ($request->break_start[$lastIndex]
                && $request->break_end[$lastIndex]) {

                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'break_start' => $request->break_start[$lastIndex],
                    'break_end' => $request->break_end[$lastIndex],
                ]);
            }

            return back();
        }

        $attendanceRequest = AttendanceRequest::where('attendance_id', $request->attendance_id)
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        // 承認済みの場合は更新
        if ($attendanceRequest && $attendanceRequest->status === 'approved') {

            $attendance = AttendanceRecord::findOrFail($request->attendance_id);

            $attendance->update([
                'clock_in' => $request->clock_in,
                'clock_out' => $request->clock_out,
                'remarks' => $request->remarks,
            ]);

            // 既存の休憩
            foreach ($attendance->breaks as $index => $break) {
                $break->update([
                    'break_start' => $request->break_start[$index],
                    'break_end' => $request->break_end[$index],
                ]);
            }

            // 新規の休憩
            $lastIndex = count($request->break_start) - 1;

            if ($request->break_start[$lastIndex]
                && $request->break_end[$lastIndex]) {

                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'break_start' => $request->break_start[$lastIndex],
                    'break_end' => $request->break_end[$lastIndex],
                ]);
            }

        } else {

            // 未申請の場合は新規レコード作成
            AttendanceRequest::create([
                'user_id' => auth()->id(),
                'attendance_id' => $request->attendance_id,
            ]);

        }
        $attendance = AttendanceRecord::findOrFail($request->attendance_id);

        return view('staff.show', compact('attendance','attendanceRequest'));

    }

    public function showApprove($id){

        $attendanceRequest = AttendanceRequest::with(
            'user',
            'attendance.breaks'
            )->findOrFail($id);
        
        return view('admin.approval', compact(
            'attendanceRequest'
        ));

    }

    public function approve($id){

        $attendanceRequest = AttendanceRequest::findOrFail($id);

        $attendanceRequest->update([
            'status' => 'approved',
        ]);

        return back();
    }

}
