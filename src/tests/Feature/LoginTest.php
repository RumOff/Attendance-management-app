<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // メールアドレス未入力
    public function test_email_is_required_for_login()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors([
            'email',
        ]);
    }

    // パスワード未入力
    public function test_password_is_required_for_login()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password',
        ]);
    }

    // ログイン情報が一致しない
    public function test_login_fails_with_invalid_credentials()
    {
        // ユーザー作成
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);

        // ログイン失敗確認
        $this->assertGuest();

        // エラー確認
        $response->assertSessionHasErrors();
    }
}
