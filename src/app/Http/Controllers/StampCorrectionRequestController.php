<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\BreakTime;
use App\Http\Requests\AttendanceUpdateRequest;
use App\Models\BreakRequest;
use Illuminate\Support\Facades\DB;

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
        
        //  管理者
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

        //  一般ユーザー
        $attendanceRequest = AttendanceRequest::where(
            'attendance_id',
            $request->attendance_id
        )
        ->where('user_id', auth()->id())
        ->latest()
        ->first();

        // 申請中は弾く
        if ($attendanceRequest && $attendanceRequest->status === 'pending') {
            return back();
        }

        // 未申請または承認済みは新規作成
        $attendanceRequest = AttendanceRequest::create([
            'user_id' => auth()->id(),
            'attendance_id' => $request->attendance_id,
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'remarks' => $request->remarks,
        ]);

        foreach ($request->break_start as $index => $breakStart) {
            if (!$breakStart) {
                continue;
            }
            BreakRequest::create([
                'attendance_request_id' => $attendanceRequest->id,
                'break_start' => $breakStart,
                'break_end' => $request->break_end[$index],
            ]);
        }

        $attendance = AttendanceRecord::findOrFail($request->attendance_id);

        return redirect()->back();

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

        $attendanceRequest = AttendanceRequest::with([
            'attendance',
            'breakRequests',
        ])->findOrFail($id);

        if ($attendanceRequest->status !== 'pending') {
            return back();
        }

        DB::transaction(function () use ($attendanceRequest) {
            $attendance = $attendanceRequest->attendance;
            $attendance->update([
                'clock_in' => $attendanceRequest->clock_in,
                'clock_out' => $attendanceRequest->clock_out,
                'remarks' => $attendanceRequest->remarks,
            ]);

            $attendance->breaks()->delete();

            foreach ($attendanceRequest->breakRequests as $breakRequest) {
                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'break_start' => $breakRequest->break_start,
                    'break_end' => $breakRequest->break_end,
                ]);
            }

            $attendanceRequest->update([
                'status' => 'approved',
            ]);
        });

        return redirect()->route('requests.index');
    }

}
