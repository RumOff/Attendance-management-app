<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\AttendanceRecord;
use App\Models\User;
use App\Models\Admin;
use App\Models\AttendanceRequest;

class AdminRequestApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_requests_are_displayed()
    {
        $admin = Admin::factory()->create();

        $user = User::factory()->create([
            'name' => '山田太郎',
        ]);

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        AttendanceRequest::factory()->create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'status' => 'pending',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(
            route('requests.index', [
                'status' => 'pending'
            ])
        );

        $response->assertStatus(200);
        $response->assertSee('山田太郎');
    }

    public function test_approved_requests_are_displayed()
    {
        $admin = Admin::factory()->create();

        $user = User::factory()->create([
            'name' => '山田太郎',
        ]);

        $attendance = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
        ]);

        AttendanceRequest::factory()->create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'status' => 'approved',
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get(
            route('requests.index', [
                'status' => 'approved'
            ])
        );

        $response->assertStatus(200);
        $response->assertSee('山田太郎');
    }

    public function test_request_detail_is_displayed()
    {
        $admin = Admin::factory()->create();

        $request = AttendanceRequest::factory()->create();

        $this->actingAs($admin, 'admin');

        $response = $this->get(
            route('admin.showApprove', [
                'id' => $request->id
            ])
        );

        $response->assertStatus(200);
    }
    public function test_admin_can_approve_request()
    {
        $admin = Admin::factory()->create();

        $request = AttendanceRequest::factory()->create([
            'status' => 'pending',
        ]);

        $this->actingAs($admin, 'admin');

        $this->patch(
            route('requests.approve', [
                'id' => $request->id
            ])
        );

        $this->assertDatabaseHas(
            'attendance_requests',
            [
                'id' => $request->id,
                'status' => 'approved',
            ]
        );
    }

}
