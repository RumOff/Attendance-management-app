<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;


    // 名前が未入力
    public function test_name_is_required()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors([
            'name'
        ]);
    }

    // メールアドレスが未入力
    public function test_email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors([
            'email'
        ]);
    }

    // パスワードが8文字未満
    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ]);

        $response->assertSessionHasErrors([
            'password'
        ]);
    }

    // パスワード確認が一致しない
    public function test_password_confirmation_does_not_match()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors([
            'password'
        ]);
    }

    // パスワード未入力
    public function test_password_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors([
            'password'
        ]);
    }

    /**
     * 正常登録
     */
    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // DBに保存されたか確認
        $this->assertDatabaseHas('users', [
            'name' => 'テスト',
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect('/attendance');
    }
}
