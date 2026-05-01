<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestBreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'break_id',
        'old_break_start',
        'new_break_start',
        'old_break_end',
        'new_break_end',
    ]

    public function request(){
        return $this->belongsTo(Request::class);
    }

    public function break()
    {
        return $this->belongsTo(BreakTime::class, 'break_id');
    }

}
