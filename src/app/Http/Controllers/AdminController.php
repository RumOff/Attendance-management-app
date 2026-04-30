<?php

namespace App\Http\Controllers;

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

    return view('admin.request');
    }
}
