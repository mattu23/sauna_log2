<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Saunalog;
use App\Services\SaunalogService; 
use App\Exceptions\NotFoundException; 
use Mockery; 


class SaunalogControllerTest extends TestCase
{

    use RefreshDatabase;

    //すべてのサウナログのページネーションを使用した一覧表示の正常テスト
    public function testGetAllLogs()
    {
        $user = User::factory()->create();
        $saunalog = Saunalog::factory()->count(5)->create(['userId' => $user->id]);
        $this->actingAs($user);

        $response = $this->getJson('/api/saunalog/all?page=1&limit=5');
        $response->assertStatus(200);
        $response->assertJsonStructure([
           'logs'=> [
                '*' => ['id', 'name', 'area', 'rank', 'comment', 'userId']
           ],
           'totalPages',
           'currentPage',
        ]);
        $response->assertJson([
            'totalPages' => 1,
            'currentPage' => 1,
        ]);
        $this->assertCount(5, Saunalog::all());   
    }


    //ユーザーに紐づくサウナログ一覧の表示機能の正常テスト
    public function testGetLogsSuccessfully() 
    {
        $user = User::factory()->create();

        //テスト用のユーザーに紐づくサウナログをデモとして3つ作成
        $saunalog = Saunalog::factory()->count(3)->create(['userId' => $user->id]);
        //テスト中に指定したユーザーとして認証された状態を模擬
        $response = $this->actingAs($user, 'web')->getJson('/api/saunalog');

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



    //特定のサウナログの表示の正常テスト
    public function testGetLogsByIdSuccessfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');
        $saunalog = Saunalog::factory()->create([
            'name' => '更新テスト',
            'area' => '東京',
            'rank' => 3,
            'comment' => '新規のサウナログ',
            'userId' => $user->id,
        ]);

        $response = $this->getJson("/api/saunalog/{$saunalog->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $saunalog->id,
            'name' => $saunalog->name,
            'area' => $saunalog->area,
            'rank' => $saunalog->rank,
            'comment' => $saunalog->comment,
        ]);
    }

    //モックを使用した特定のサウナログの取得失敗テスト
    public function testGetLogByIdFailed()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        // モックを作成して、NotFoundExceptionを投げるように設定
        $mock = Mockery::mock(SaunalogService::class);
        $mock->shouldReceive('getLogById')->once()->andThrow(new NotFoundException("指定されたサウナログが見つかりません。"));
        $this->app->instance(SaunalogService::class, $mock);

        $nonexistentLogId = 999;//　存在しないIDを仮定
        $response = $this->getJson("/api/saunalog/{$nonexistentLogId}");

        $response->assertStatus(404);
        $response->assertJson(['message' => '指定されたサウナログが見つかりません。']);
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

    // 特定のサウナログの編集異常テスト
    /** @test */
    public function testUpdateSaunalogFailed() {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $this->mock(SaunalogService::class, function($mock) {
            $mock->shouldReceive('updateLogById')->once()->andThrow(new NotFoundException("指定されたサウナログが見つかりません。"));
        });

        $response = $this->putJson('api/saunalog/999', [
            'name' => 'テスト1000',
            'area' => 'さいたま',
            'rank' => 5,
            'comment' => '更新のサウナログ',
        ]);
        $response->assertStatus(404);
        $response->assertJson(['message' => '指定されたサウナログが見つかりません。']);
    }



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

    //サウナログの削除の異常テスト
    public function testDeleteSaunalogFailed() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->mock(SaunalogService::class, function($mock) {
            $mock->shouldReceive('deleteLogById')->once()->andThrow(new NotFoundException("指定されたサウナログが見つかりません。"));
        });

        $response = $this->deleteJson('/api/saunalog/999');
        $response->assertStatus(404);
        $response->assertJson(['message' => '指定されたサウナログが見つかりません。']);
    }
}
