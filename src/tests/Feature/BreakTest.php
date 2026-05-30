<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\BreakTime;
use Carbon\Carbon;

class BreakTest extends TestCase
{
    use RefreshDatabase;

    public function test_break_start_button_is_displayed()
    {
        $user = User::factory()->create();

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance');

        $response->assertSee('休憩入');
    }

    public function test_user_can_start_break()
    {
        $user = User::factory()->create();

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
        ]);

        $this->actingAs($user);

        $this->post(route('staff.store'), [
            'action' => 'break_start',
        ]);

        $this->assertDatabaseHas('break_times', [
            'break_end' => null,
        ]);
    }

    public function test_user_can_take_break_multiple_times()
    {
        $user = User::factory()->create();

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
            'break_end' => now(),
        ]);

        $this->actingAs($user);

        $this->post(route('staff.store'), [
            'action' => 'break_start',
        ]);

        $this->post(route('staff.store'), [
            'action' => 'break_end',
        ]);

        $response = $this->get('/attendance');

        $response->assertSee('休憩入');
    }

    public function test_break_end_button_is_displayed()
    {
        $user = User::factory()->create();

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
            'break_end' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get('/attendance');

        $response->assertSee('休憩戻');
    }

    public function test_user_can_end_break()
    {
        $user = User::factory()->create();

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
            'break_end' => null,
        ]);

        $this->actingAs($user);

        $this->post(route('staff.store'), [
            'action' => 'break_end',
        ]);

        $this->assertDatabaseMissing('break_times', [
            'attendance_id' => $attendance->id,
            'break_end' => null,
        ]);
    }


    public function test_break_time_is_displayed_in_attendance_list()
    {
        $user = User::factory()->create();

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'clock_in' => Carbon::parse('09:00'),
            'clock_out' => Carbon::parse('18:00'),
        ]);

        BreakTime::factory()->create([
            'attendance_id' => $attendance->id,
            'break_start' => Carbon::parse('12:00'),
            'break_end' => Carbon::parse('13:00'),
        ]);

        $this->actingAs($user);

        $response = $this->get(route('staff.history'));

        $response->assertStatus(200);

        $response->assertSee('1:00');
    }

}
