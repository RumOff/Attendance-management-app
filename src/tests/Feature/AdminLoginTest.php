<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * メールアドレス未入力
     */
    public function test_email_is_required_for_admin_login()
    {
        $response = $this->post('/admin/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors([
            'email',
        ]);
    }

    /**
     * パスワード未入力
     */
    public function test_password_is_required_for_admin_login()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password',
        ]);
    }

    /**
     * ログイン情報不一致
     */
    public function test_admin_login_fails_with_invalid_credentials()
    {
        // 管理者作成
        Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'wrong@example.com',
            'password' => 'password',
        ]);

        // adminガードで未ログイン確認
        $this->assertGuest('admin');

        // エラー確認
        $response->assertSessionHasErrors();
    }
}
