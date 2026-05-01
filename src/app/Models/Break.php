<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'break_start',
        'break_end',
    ];

    public function attendance(){
        return $this->belongsTo(AttendanceRecord::class, 'attendance_id');
    }

    public function requestBreaks(){
        return $this->hasMany(RequestBreak::class, 'break_id');
    }
}
