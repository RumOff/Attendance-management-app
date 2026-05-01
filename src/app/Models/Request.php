<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'applicable_date',
        'reason',
        'status',
    ]

    public function attendance(){
        return $this->belongsTo(AttendanceRecord::class, 'attendance-id');
    }

    public function attendanceFix(){
        return $this->hasOne(RequestAttendance::class);
    }

    public function breakFix(){
        return $this->hasMany(RequestBreake::class);
    }
}
