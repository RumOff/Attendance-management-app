<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\BreakTime;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(){

        $attendance = AttendanceRecord::where('user_id', auth()->id())
        ->where('date', now()->toDateString())
        ->first();

        $status = '勤務外';

        if ($attendance) {
            $breakTime = $attendance->breaks()
                ->whereNull('break_end')
                ->first();

            if ($attendance->clock_out) {
                $status = '退勤済み';
            } elseif ($breakTime) {
                $status = '休憩中';
            } else {
                $status = '出勤中';
            }

        }

        return view('staff.index', compact('attendance', 'status'));
    }


    public function store(Request $request){

        // 今日の勤怠取得
        $attendance = AttendanceRecord::where('user_id', auth()->id())
            ->where('date', now()->toDateString())
            ->first();

        $action = $request->input('action');

        // 出勤
        if ($action === 'clock_in'){
            if ($attendance) {
                return back()->with('error', 'すでに出勤済みです');
            }

            AttendanceRecord::create([
                'user_id' => auth()->id(),
                'date' => now()->toDateString(),
                'clock_in' => now(),
            ]);
        }

        // 退勤
        if ($action === 'clock_out') {
            if (!$attendance || $attendance->clock_out) {
                return back()->with('error', 'すでに退勤済みです');
            }

            $attendance->update([
                'clock_out' => now(),
            ]);
        }

        // 休憩開始
        if ($action === 'break_start'){
            if (!$attendance || $attendance->clock_out) {
                return back()->with('error', '不正な操作です');
            }

            BreakTime::create([
                'attendance_id' => $attendance->id,
                'break_start' => now(),
            ]);
        }

        // 休憩終了
        if ($action === 'break_end') {
            if (!$attendance || $attendance->clock_out) {
                return back()->with('error', '不正な操作です');
            }

            $breakTime = $attendance->breaks()
                ->whereNull('break_end')
                ->first();

            if (!$breakTime) {
                return back()->with('error', '不正な操作です');
            }

            $breakTime->update([
                'break_end' => now(),
            ]);
        }

        return back();

    }

    public function show(){

        return view('staff.show');
    }

    public function history(){

        return view('staff.history');
    }
}
