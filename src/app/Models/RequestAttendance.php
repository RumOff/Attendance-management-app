<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'old_clock_in',
        'new_clock_in',
        'old_clock_out',
        'new_clock_out',
    ]

    public function request(){
        return $this->belongsTo(Request::class);
    }
}
