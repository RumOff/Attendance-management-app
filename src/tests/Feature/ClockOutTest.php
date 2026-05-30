<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class ClockOutTest extends TestCase
{
    use RefreshDatabase;

    public function test_clock_out_button_is_displayed()
    {
        $user = User::factory()->create();

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'clock_out' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance');

        $response->assertSee('退勤');
    }

    public function test_user_can_clock_out()
    {
        $user = User::factory()->create();

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'clock_out' => null,
        ]);

        $this->actingAs($user);

        $this->post(route('staff.store'), [
            'action' => 'clock_out',
        ]);

        $attendance->refresh();

        $this->assertNotNull($attendance->clock_out);
    }

    public function test_clock_out_time_is_displayed_in_attendance_list()
    {
        Carbon::setTestNow(
            Carbon::create(2026, 6, 30, 9, 0, 0)
        );

        $user = User::factory()->create();

        $this->actingAs($user);

        // 出勤
        $this->post(route('staff.store'), [
            'action' => 'clock_in',
        ]);

        // 退勤時刻を変更
        Carbon::setTestNow(
            Carbon::create(2026, 6, 30, 18, 0, 0)
        );

        // 退勤
        $this->post(route('staff.store'), [
            'action' => 'clock_out',
        ]);

        $response = $this->get(route('staff.history'));

        $response->assertSee('18:00');
    }

}
