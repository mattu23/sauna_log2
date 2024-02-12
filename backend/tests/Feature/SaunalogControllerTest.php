<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class SaunalogControllerTest extends TestCase
{
    //サウナログの新規作成テスト
    /** @test */
    public function testCreateSaunalog() 
    {
        //テスト用のユーザーデータをデータベースに実際に挿入
        $user = User::factory()->create();
        //テスト中に指定したユーザーとして認証された状態を模擬
        $this->actingAs($user, 'web');

        $logData = [
            'name' => 'サウナ東京',
            'area' => '東京都 港区',
            'rank' => 5,
            'comment' => '最高のサウナ体験でした！',
        ];

        //指定されたルートに対してJSON形式のPOSTリクエストを送信。APIエンドポイントに対するHTTPリクエストをテスト
        $response = $this->postJson('/api/saunalog', $logData);
        //レスポンスのステータスコードが201（Created）であることを検証
        $response->assertStatus(201);
        //指定したデータがデータベースのsaunalogsテーブルに存在することを検証
        $this->assertDatabaseHas('saunalogs', $logData);
    }
}
