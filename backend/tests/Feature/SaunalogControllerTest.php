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


    //ユーザーに紐づくサウナログ一覧の表示機能の正常テスト
    public function testGetLogsSuccessfully() 
    {
        $user = User::factory()->create();

        //テスト用のユーザーに紐づくサウナログをデモとして3つ作成
        $saunalog = Saunalog::factory()->count(3)->create(['userId' => $user->id]);
        //テスト中に指定したユーザーとして認証された状態を模擬
        $response = $this->actingAs($user)->getJson('/api/saunalog');

        $response->assertStatus(200);
        //レスポンスボディが作成したサウナログの数だけレスポンスに含まれているか検証
        $response->assertJsonCount(3); 
        //配列の各要素が以下の構造を持つことを検証
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'area',
                'rank',
                'comment',
            ],
        ]);
    }


    //サウナログの新規作成の正常テスト
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

    //サウナログの新規作成の異常テスト
    /** @test */



    //サウナログの更新の正常テスト
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

    // 指定されたIDのサウナログが見つからない異常テスト
    /** @test */



    //サウナログの削除の正常テスト
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
