<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttendanceRecord;
use App\Models\RequestBreak;

class BreakTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'break_start',
        'break_end',
    ];

    protected $casts = [
        'break_start' => 'datetime',
        'break_end' => 'datetime',
    ];

    public function attendance(){
        return $this->belongsTo(AttendanceRecord::class, 'attendance_id');
    }

    public function requestBreaks(){
        return $this->hasMany(RequestBreak::class, 'break_id');
    }
}
