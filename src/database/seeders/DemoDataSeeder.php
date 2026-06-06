<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\BreakTime;
use App\Models\AttendanceRecord;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
        ['name' => '野原ひろし', 'email' => 'nohara@example.com'],
        ['name' => '野比のび助', 'email' => 'nobi@example.com'],
        ['name' => 'フグ田マスオ', 'email' => 'fuguta@example.com'],
        ];

        foreach ($users as $data) {

            $user = User::factory()->create($data);

            $date = Carbon::now()->subMonth();

            while ($date->lte(Carbon::now())) {

                if (!$date->isWeekend()) {

                    $attendance = AttendanceRecord::create([
                        'user_id' => $user->id,
                        'date' => $date->toDateString(),
                        'clock_in' => $date->copy()->setTime(9, 0),
                        'clock_out' => $date->copy()->setTime(18, 0),
                    ]);

                    BreakTime::create([
                        'attendance_id' => $attendance->id,
                        'break_start' => '12:00:00',
                        'break_end' => '13:00:00',
                    ]);
                }

                $date->addDay();
            }
        }
    }
}
