<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(){

        return view('staff.index');
    }

    public function store(){

        return view('staff.index');
    }

    public function show(){

        return view('staff.show');
    }

    public function history(){

        return view('staff.history');
    }
}
