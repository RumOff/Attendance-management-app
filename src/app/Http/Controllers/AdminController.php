<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\BreakTime;
use App\Models\AttendanceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminController extends Controller
{
    public function showLoginForm(){

    return view('auth.admin-login');
    }

    public function login(Request $request){

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {

            $request->session()->regenerate();

            return redirect('/admin/attendance/list');
        }

        return back()->withErrors([
            'email' => 'ログインできません',
        ]);
    }

    public function history(Request $request){
        $date = $request->input('date');
        $currentDate = $date
            ? Carbon::createFromFormat('Y-m-d', $date)
            : Carbon::now();
        
        $attendances = AttendanceRecord::whereDate('date', $currentDate)
            ->with('breaks')
            ->with('user')
            ->get();
        return view('admin.history', compact('attendances', 'currentDate'));
    }

    public function show(){

        return view('admin.show');
    }

    public function staffList(){

        return view('admin.staff');
    }

    public function staffAttendance(){

        return view('admin.staff-attendance');
    }

    public function approve(){

        return view('admin.approval');
    }

    public function requests(){
        $requests = AttendanceRequest::with('attendance')
        ->where('user_id', auth()->id())
        ->get();

        return view('admin.request', compact('requests'));
    }
}
