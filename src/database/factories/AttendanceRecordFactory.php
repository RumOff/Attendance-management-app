<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AttendanceRecord;
use App\Models\User;

class AttendanceRecordFactory extends Factory
{
    protected $model = AttendanceRecord::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'date' => now()->toDateString(),
            'clock_in' => '09:00:00',
            'clock_out' => null,
        ];
    }
}
