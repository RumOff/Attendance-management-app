<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRequest extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';

    protected $fillable = [
        'user_id',
        'attendance_id',
        'clock_in',
        'clock_out',
        'remarks',
        'status',
    ];

    public function attendance(){
        return $this->belongsTo(AttendanceRecord::class, 'attendance_id');
    }

    public function breakFix(){
        return $this->hasMany(RequestBreak::class);
    }
}
