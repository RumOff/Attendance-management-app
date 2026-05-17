<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceRequest;

class StampCorrectionRequestController extends Controller
{
    public function index(){

        // ******** 管理者 ********
        if (Auth::guard('admin')->check()) {
            
            // 管理者用データ取得
            $requests = AttendanceRequest::with('user')->get();
            
            return view('admin.request', compact('requests'));
        }

        // ******** 一般ユーザー ********
        $requests = AttendanceRequest::with('attendance')
        ->where('user_id', auth()->id())
        ->with('user')
        ->get();
        
        return view('admin.request', compact('requests'));
        
    }


    public function storeRequests(Request $request){

        AttendanceRequest::create([
            'user_id' => auth()->id(),
            'attendance_id' => $request->attendance_id,
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'remarks' => $request->remarks,
            
        ]);

        return redirect()->route('staff.requests');
    }

    public function approve(){

        return view('admin.approval');
    }
}
