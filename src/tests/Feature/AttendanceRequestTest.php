<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\AttendanceRecord;
use App\Models\User;

class AttendanceRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_clock_in_cannot_be_after_clock_out()
    {
        $user = User::factory()->create();

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->post(
            route('requests.storeRequests'),
            [
                'attendance_id' => $attendance->id,
                'clock_in' => '19:00',
                'clock_out' => '18:00',
                'remarks' => '修正申請',
            ]
        );

        $response->assertSessionHasErrors('clock_in');
    }

    public function test_break_start_cannot_be_after_clock_out()
    {
        $user = User::factory()->create();

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->post(
            route('requests.storeRequests'),
            [
                'attendance_id' => $attendance->id,
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'break_start' => ['19:00'],
                'break_end' => ['19:30'],
                'remarks' => '修正申請',
            ]
        );

        $response->assertSessionHasErrors('break_start.0');
    }

    public function test_break_end_cannot_be_after_clock_out()
    {
        $user = User::factory()->create();

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->post(
            route('requests.storeRequests'),
            [
                'attendance_id' => $attendance->id,
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'break_start' => ['12:00'],
                'break_end' => ['19:00'],
                'remarks' => '修正申請',
            ]
        );

        $response->assertSessionHasErrors('break_end.0');
    }

    public function test_remarks_is_required()
    {
        $user = User::factory()->create();

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

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

    public function test_attendance_request_is_created()
    {
        $user = User::factory()->create();

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $this->post(route('requests.storeRequests'), [
            'attendance_id' => $attendance->id,
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'remarks' => '修正申請',
        ]);

        $this->assertDatabaseHas('attendance_requests', [
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
        ]);
    }
}
