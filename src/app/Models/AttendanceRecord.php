<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BreakTime;
use App\Models\AttendanceRequest;
use App\Models\User;
use Carbon\Carbon;

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

    public function user(){
        return $this->belongsTo(user::class);
    }

    public function getTotalMinutesAttribute(){
        // 未出勤、未退勤
        if (!$this->clock_in || !$this->clock_out) {
            return null;
        }

        // 休憩から戻ってない場合はNull
        $hasOngoingBreak = $this->breaks->contains(function ($break) {
            return $break->break_start && !$break->break_end;
        });

        if ($hasOngoingBreak) {
            return null;
        }

        // 勤務時間
        $workMinutes = Carbon::parse($this->clock_out)
            ->diffInMinutes($this->clock_in);

        // 休憩合計
        $breakMinutes = $this->breaks->sum(function ($break) {
            return Carbon::parse($break->break_end)
                ->diffInMinutes($break->break_start);
        });

        // 合計
        return $workMinutes - $breakMinutes;
    }

    public function getBreakMinutesAttribute(){

        // 休憩から戻ってない場合はNull
        $hasOngoingBreak = $this->breaks->contains(function ($break) {
            return $break->break_start && !$break->break_end;
        });

        if ($hasOngoingBreak) {
            return null;
        }

        return $this->breaks->sum(function ($break) {
            return Carbon::parse($break->break_end)
                ->diffInMinutes($break->break_start);
        });
    }
}