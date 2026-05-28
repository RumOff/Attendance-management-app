<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;

class AttendanceDateTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_datetime_is_displayed()
    {
        // 現在日時固定
        Carbon::setTestNow(
            Carbon::create(2026, 5, 28, 8, 30)
        );

        // ユーザー作成
        $user = User::factory()->create();

        // ログイン
        $this->actingAs($user);

        // 画面アクセス
        $response = $this->get('/attendance');

        // 日付確認
        $response->assertSee('2026年5月28日(木)');

        // 時間確認
        $response->assertSee('08:30');
    }
}
