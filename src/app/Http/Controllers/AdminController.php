<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function history(){

    return view('admin.history');
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
