<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\Admin;
use Carbon\Carbon;

class AdminStaffAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_see_all_users()
    {
        $admin = Admin::factory()->create();

        $user1 = User::factory()->create([
            'name' => 'test1',
            'email' => 'test1@test.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'test2',
            'email' => 'test2@test.com',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.staffList'));

        $response->assertSee('test1');
        $response->assertSee('test1@test.com');

        $response->assertSee('test2');
        $response->assertSee('test2@test.com');
    }

    public function test_admin_can_see_staff_attendance()
    {
        $admin = Admin::factory()->create();

        $user = User::factory()->create();

        Carbon::setTestNow(
            Carbon::create(2026, 6, 15)
        );

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-06-01',
            'clock_in' => '2026-06-01 09:00:00',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(
            route('admin.staffAttendance', [
                'id' => $user->id
            ])
        );

        $response->assertSee('09:00');
    }

    public function test_previous_month_attendance_is_displayed()
    {
        $admin = Admin::factory()->create();

        $user = User::factory()->create();

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-05-10',
            'clock_in' => '2026-05-10 09:00:00',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(
            route('admin.staffAttendance', [
                'id' => $user->id,
                'month' => '2026-05',
            ])
        );

        $response->assertSee('09:00');
    }

    public function test_next_month_attendance_is_displayed()
    {
        $admin = Admin::factory()->create();

        $user = User::factory()->create();

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-07-10',
            'clock_in' => '2026-07-10 09:00:00',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(
            route('admin.staffAttendance', [
                'id' => $user->id,
                'month' => '2026-07',
            ])
        );

        $response->assertSee('09:00');
    }

    public function test_admin_can_open_attendance_detail()
    {
        $admin = Admin::factory()->create();

        $user = User::factory()->create();

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(
            route('admin.show', [
                'id' => $attendance->id
            ])
        );

        $response->assertStatus(200);
    }

}
