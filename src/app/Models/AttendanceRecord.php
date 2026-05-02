<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BreakTime;
use App\Models\AttendanceRequest;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'remarks',
    ];

    public function breaks(){
        return $this->hasMany(BreakTime::class, 'attendance_id');
    }

    public function requests(){
        return $this->hasMany(AttendanceRequest::class);
    }
}