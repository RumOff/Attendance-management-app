<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BreakTime;
use App\Models\AttendanceRecord;

class BreakTimeFactory extends Factory
{
    protected $model = BreakTime::class;

    public function definition()
    {
        return [
            'attendance_id' => AttendanceRecord::factory(),
            'break_start' => '12:00:00',
            'break_end' => null,
        ];
    }
}
