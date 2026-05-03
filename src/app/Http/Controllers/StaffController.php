<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\BreakTime;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

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

    public function history(Request $request){
        $month = $request->input('month');
        $currentMonth = $month
            ? Carbon::createFromFormat('Y-m', $month)
            : Carbon::now();

        // 該当月のはじめと終わりの日にち取得(copy()で$currentMonthを書き換えない)
        $start = $currentMonth->copy()->startOfMonth();
        $end = $currentMonth->copy()->endOfMonth();

        // 日付のリスト
        $dates = CarbonPeriod::create($start, $end);

        $attendances = AttendanceRecord::where('user_id', auth()->id())
            ->whereBetween('date', [$start, $end])
            ->with('breaks')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
        });
        // 日付をキーにした配列に変換 ↑

        return view('staff.history', compact('dates', 'attendances', 'currentMonth'));
    }

    public function show($attendance_id){

        $attendance = AttendanceRecord::where('id', $attendance_id)
            ->with('breaks')
            ->get();

        return view('staff.show', compact('attendance'));
    }
}
