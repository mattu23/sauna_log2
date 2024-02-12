<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Saunalog;



class SaunalogControllerTest extends TestCase
{

    use RefreshDatabase;


    //サウナログの新規作成が成功する場合のテスト
    /** @test */
    public function testCreateSaunalogSuccessfully() 
    {
        //テスト用のユーザーデータをデータベースに実際に挿入
        $user = User::factory()->create();
        //テスト中に指定したユーザーとして認証された状態を模擬
        $this->actingAs($user, 'web');

        $saunalog = [
            'name' => 'サウナ東京',
            'area' => '東京都 港区',
            'rank' => 5,
            'comment' => '最高のサウナ体験でした！',
        ];

        //指定されたルートに対してJSON形式のPOSTリクエストを送信。APIエンドポイントに対するHTTPリクエストをテスト
        $response = $this->postJson('/api/saunalog', $saunalog);
        //レスポンスのステータスコードが201（Created）であることを検証
        $response->assertStatus(201);
        //指定したデータがデータベースのsaunalogsテーブルに存在することを検証
        $this->assertDatabaseHas('saunalogs', $saunalog);
    }

    //サウナログの新規作成のエラーテスト
    /** @test */



    //サウナログの更新が成功する場合のテスト
    /** @test */
    public function testUpdateSaunalogSuccessfully() {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');
        $saunalog = Saunalog::factory()->create([
            'name' => '更新テスト',
            'area' => '東京',
            'rank' => 3,
            'comment' => '新規のサウナログ',
            'userId' => $user->id,
        ]);
        $updateLogData = [
            'name' => 'テスト1000',
            'area' => 'さいたま',
            'rank' => 5,
            'comment' => '更新のサウナログ',
        ];
        $response = $this->putJson("/api/saunalog/{$saunalog->id}", $updateLogData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('saunalogs', [
            ...$updateLogData,
            'id'=> $saunalog->id,
        ]);
    }

    // 指定されたIDのサウナログが見つからない場合のテスト
    /** @test */



    //サウナログの削除が成功する場合のテスト
    /** @test */
    public function testDeleteSaunalogSuccessfully() {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');
        $saunalog = Saunalog::factory()->create([
            'name' => '削除テスト',
            'area' => '東京',
            'rank' => 3,
            'comment' => '新規のサウナログ',
            'userId' => $user->id,
        ]);
        $response = $this->deleteJson("/api/saunalog/{$saunalog->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('saunalogs', ['id' => $saunalog->id]);
    }
}
