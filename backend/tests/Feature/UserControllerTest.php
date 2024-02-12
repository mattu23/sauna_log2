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



    //ユーザーログインの正常テスト
    public function testSignInSuccessfully()
    {
        //テスト用ユーザーの作成
        $testUser = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password1234'),
        ]); 
        //ログインデータ
        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password1234',
        ];
        //ログインリクエストを送信
        $response = $this->postJson('/api/signin', $loginData);
        //レスポンスステータスコードが200であることを検証
        $response->assertStatus(200);
        //レスポンスにトークンとユーザー情報が含まれていることを検証
        $response->assertJsonStructure([
            'token',
            'user' => [
                'id',
                'username',
                'email',
            ],
        ]);
        //データベースにユーザーが存在することを検証
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }



    //ユーザーログアウトの正常テスト
    public function testLogoutSuccessfully() 
    {
        $testUser = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password1234'),
        ]); 
        $loginData = [
            'email' => 'test@example.com',
            'password' => 'password1234',
        ];
        $loginResponse = $this->postJson('/api/signin', $loginData);
        // トークンを取得
        $token = $loginResponse->json('token');
        // トークンを使用してログアウトリクエストを送信
        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');        
        $logoutResponse->assertStatus(200);
    }
}
