<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Saunalog;


class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    //ユーザー新規作成の正常テスト
    public function testSignUpSuccessfully()
    {
        //バリデーションルールに適合するテスト用のユーザーデータを準備
        $testUser = [
            'username' => 'TestUser1',
            'email' => 'test@example.com',
            'password' => 'password1234',
        ];
        //新規登録APIを呼び出し
        $response = $this->postJson('/api/signup', $testUser);
        //レスポンスステータスコードが201であることを検証
        $response->assertStatus(201);
        //データベースにユーザーが正しく保存されていることを検証
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);
    }
}
