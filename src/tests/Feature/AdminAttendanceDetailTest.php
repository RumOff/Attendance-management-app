<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\Admin;

class AdminAttendanceDetailTest extends TestCase
{
    use RefreshDatabase;
    public function test_admin_can_see_attendance_detail()
{
    $admin = Admin::factory()->create();

    $user = User::factory()->create([
        'name' => 'test',
    ]);

    $attendance = AttendanceRecord::factory()->create([
        'user_id' => $user->id,
        'date' => '2026-06-01',
        'clock_in' => '2026-06-01 09:00:00',
        'clock_out' => '2026-06-01 18:00:00',
    ]);

    $this->actingAs($admin, 'admin');

    $response = $response = $this->get(route('admin.show', $attendance->id));

    $response->assertSee('test');
    $response->assertSee('2026年');
    $response->assertSee('6月1日');
    $response->assertSee('09:00');
    $response->assertSee('18:00');
}

public function test_admin_cannot_set_clock_in_after_clock_out()
{
    $admin = Admin::factory()->create();

    $attendance = AttendanceRecord::factory()->create();

    $this->actingAs($admin, 'admin');

    $response = $this->post(
        route('requests.storeRequests'),
        [
            'attendance_id' => $attendance->id,
            'clock_in' => '19:00',
            'clock_out' => '18:00',
            'remarks' => '修正',
        ]
    );

    $response->assertSessionHasErrors('clock_in');
}
public function test_admin_cannot_set_break_start_after_clock_out()
{
    $admin = Admin::factory()->create();

    $attendance = AttendanceRecord::factory()->create();

    $this->actingAs($admin, 'admin');

    $response = $this->post(
        route('requests.storeRequests'),
        [
            'attendance_id' => $attendance->id,
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'break_start' => ['19:00'],
            'break_end' => ['19:30'],
            'remarks' => '修正',
        ]
    );

    $response->assertSessionHasErrors('break_start.0');
}
public function test_admin_cannot_set_break_end_after_clock_out()
{
    $admin = Admin::factory()->create();

    $attendance = AttendanceRecord::factory()->create();

    $this->actingAs($admin, 'admin');

    $response = $this->post(
        route('requests.storeRequests'),
        [
            'attendance_id' => $attendance->id,
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'break_start' => ['12:00'],
            'break_end' => ['19:00'],
            'remarks' => '修正',
        ]
    );

    $response->assertSessionHasErrors('break_end.0');
}

public function test_admin_must_enter_remarks()
{
    $admin = Admin::factory()->create();

    $attendance = AttendanceRecord::factory()->create();

    $this->actingAs($admin, 'admin');

    $response = $this->post(
        route('requests.storeRequests'),
        [
            'attendance_id' => $attendance->id,
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'remarks' => '',
        ]
    );

    $response->assertSessionHasErrors('remarks');
}
}
