<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\AttendanceRequest;

class AdminController extends Controller
{
    public function showLoginForm(){
        return view('auth.admin-login');
    }

    public function login(AdminLoginRequest $request){
    
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {

            $request->session()->regenerate();

            return redirect('/admin/attendance/list');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    public function logout(Request $request){
    
        Auth::guard('admin')->logout();

        return redirect('/admin/login');
    }

    public function history(Request $request){
        $currentDate = $request->date
            ? Carbon::parse($request->date)
            : Carbon::today();
            
        $attendances = AttendanceRecord::whereDate('date', $currentDate)
            ->with('breaks')
            ->with('user')
            ->get();

        // 前日と翌日を作る
        $prevDate = $currentDate->copy()->subDay();
        $nextDate = $currentDate->copy()->addDay();

        return view('admin.history', compact('attendances', 'currentDate','prevDate' ,'nextDate'));
    }

    public function show($id){
        $attendance = AttendanceRecord::with('user', 'breaks')
        ->findOrFail($id);

        $attendanceRequest = AttendanceRequest::where(
            'attendance_id',
            $attendance->id
        )
        ->latest()
        ->first();

        return view('admin.show', compact('attendance', 'attendanceRequest'));
    }

    public function staffList(){
        $staffs = User::get();

        return view('admin.staff', compact('staffs'));
    }

    public function staffAttendance(Request $request, $id){
        $user = User::findOrFail($id);

        $month = $request->input('month');
        $currentMonth = $month
            ? Carbon::createFromFormat('Y-m', $month)
            : Carbon::now();

        $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

        // 該当月のはじめと終わりの日にち取得(copy()で$currentMonthを書き換えない)
        $start = $currentMonth->copy()->startOfMonth();
        $end = $currentMonth->copy()->endOfMonth();

        // 日付のリスト
        $dates = CarbonPeriod::create($start, $end);

        $attendances = AttendanceRecord::where('user_id', $id)
            ->whereBetween('date', [$start, $end])
            ->with('breaks')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
        });
        // 日付をキーにした配列に変換 ↑

        return view('admin.staff-attendance', compact('user', 'dates', 'attendances', 'currentMonth','prevMonth','nextMonth'));
    }

    public function exportCsv(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $month = $request->input('month');

        $currentMonth = $month
            ? Carbon::createFromFormat('Y-m', $month)
            : Carbon::now();

        $start = $currentMonth->copy()->startOfMonth();
        $end = $currentMonth->copy()->endOfMonth();

        $attendances = AttendanceRecord::where('user_id', $id)
            ->whereBetween('date', [$start, $end])
            ->with('breaks')
            ->orderBy('date')
            ->get();

        $fileName = sprintf(
            '%s_%s.csv',
            $user->name,
            $currentMonth->format('Y-m')
        );

        return response()->streamDownload(function () use ($attendances) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                '日付',
                '退勤',
                '出勤',
                '休憩',
                '合計',
            ]);

            foreach ($attendances as $attendance) {

                $breakMinutes = $attendance->breaks->sum(function ($break) {
                    if (!$break->break_start || !$break->break_end) {
                        return 0;
                    }

                    return $break->break_end->diffInMinutes(
                        $break->break_start
                    );
                });

                $breakTime = sprintf(
                    '%02d:%02d',
                    floor($breakMinutes / 60),
                    $breakMinutes % 60
                );

                $workMinutes = 0;

                if ($attendance->clock_in && $attendance->clock_out) {

                    $workMinutes =
                        $attendance->clock_out->diffInMinutes(
                            $attendance->clock_in
                        ) - $breakMinutes;
                }

                $workTime = sprintf(
                    '%02d:%02d',
                    floor($workMinutes / 60),
                    $workMinutes % 60
                );

                fputcsv($handle, [
                    $attendance->date,
                    optional($attendance->clock_in)->format('H:i'),
                    optional($attendance->clock_out)->format('H:i'),
                    $breakTime,
                    $workTime,
                ]);
            }

            fclose($handle);

        }, $fileName);
    }

}
