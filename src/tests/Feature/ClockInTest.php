<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;

class ClockInTest extends TestCase
{
    use RefreshDatabase;
    public function test_clock_in_button_is_displayed()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/attendance');

        $response->assertSee('出勤');
    }

    public function test_user_can_clock_in()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post(route('staff.store'), [
            'action' => 'clock_in',
        ]);

        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
            'date' => now()->toDateString(),
        ]);
    }

    public function test_clock_in_button_is_not_displayed_after_clock_out()
    {
        $user = User::factory()->create();

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'clock_out' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance');

        $response->assertDontSee('出勤');
    }

    public function test_clock_in_time_is_displayed_in_attendance_list()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // 出勤
        $this->post(route('staff.store'), [
            'action' => 'clock_in',
        ]);

        // 一覧画面
        $response = $this->get(route('staff.history'));

        // 今日の出勤時刻が表示される
        $response->assertSee(now()->format('H:i'));
    }
}
