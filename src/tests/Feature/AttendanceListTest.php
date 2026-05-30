<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use App\Models\User;
use App\Models\AttendanceRecord;

class AttendanceListTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_see_own_attendance_records()
    {

        Carbon::setTestNow(
            Carbon::create(2026, 6, 15)
        );

        $user = User::factory()->create();

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-06-01',
            'clock_in' => '2026-06-01 09:00:00',
        ]);

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-06-02',
            'clock_in' => '2026-06-02 10:00:00',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('staff.history'));

        $response->assertSee('09:00');
        $response->assertSee('10:00');
    }

    public function test_current_month_is_displayed()
    {
        Carbon::setTestNow(
            Carbon::create(2026, 6, 15)
        );

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('staff.history'));

        $response->assertSee('2026/06');
    }

    public function test_previous_month_attendance_is_displayed()
    {
        $user = User::factory()->create();

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-05-10',
            'clock_in' => '2026-05-10 09:00:00',
        ]);

        $this->actingAs($user);

        $response = $this->get(
            route('staff.history', [
                'month' => '2026-05'
            ])
        );

        $response->assertSee('09:00');
    }

    public function test_next_month_attendance_is_displayed()
    {
        $user = User::factory()->create();

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-07-10',
            'clock_in' => '2026-07-10 09:00:00',
        ]);

        $this->actingAs($user);

        $response = $this->get(
            route('staff.history', [
                'month' => '2026-07'
            ])
        );

        $response->assertSee('09:00');
    }

    public function test_user_can_open_attendance_detail()
    {
        $user = User::factory()->create();

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(
            '/attendance/detail/' . $attendance->id
        );

        $response->assertStatus(200);
    }

}
