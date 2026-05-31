<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\AttendanceRecord;
use App\Models\User;
use App\Models\Admin;
use Carbon\Carbon;

class AdminAttendanceListTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_see_all_users_attendance()
    {
        $admin = Admin::factory()->create();

        $user1 = User::factory()->create([
            'name' => 'test1',
        ]);

        $user2 = User::factory()->create([
            'name' => 'test2',
        ]);

        AttendanceRecord::factory()->create([
            'user_id' => $user1->id,
            'date' => now()->toDateString(),
        ]);

        AttendanceRecord::factory()->create([
            'user_id' => $user2->id,
            'date' => now()->toDateString(),
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.history'));

        $response->assertSee('test1');
        $response->assertSee('test2');
    }

    public function test_current_date_is_displayed()
    {
        Carbon::setTestNow(
            Carbon::create(2026, 6, 1)
        );

        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.history'));

        $response->assertSee('2026年6月1日');
    }

    public function test_previous_day_attendance_is_displayed()
    {
        $admin = Admin::factory()->create();

        $user = User::factory()->create();

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-05-31',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(
            route('admin.history', [
                'date' => '2026-05-31'
            ])
        );

        $response->assertSee('05/31');
    }

    public function test_next_day_attendance_is_displayed()
    {
        $admin = Admin::factory()->create();

        $user = User::factory()->create();

        AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-06-02',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(
            route('admin.history', [
                'date' => '2026-06-02'
            ])
        );

        $response->assertSee('06/02');
    }

}
