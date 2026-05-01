<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(Break::class, 'attendance_id');
    }

    public function requests(){
        return $this->hasMany(Request::class);
    }
}
