<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saunalog;
use App\Models\User;
use App\Http\Requests\CreateSaunalogRequest; // リクエストバリデーションクラス
use App\Http\Requests\UpdateSaunalogRequest; // リクエストバリデーションクラス
use App\Services\SaunalogService; // Saunalogサービスクラス
use App\Exceptions\CustomException;
use App\Exceptions\NotFoundException;


class SaunalogController extends Controller
{
    private $saunalogService;

    public function __construct(SaunalogService $saunalogService)
    {
        $this->middleware('auth');
        $this->saunalogService = $saunalogService;
    }

    //ユーザーに紐づくサウナログ一覧の表示
    public function getLogs(Request $request)
    {
        try {
            $userId = $request->user()->id;
            return $this->saunalogService->getLogsByUser($userId);
        } catch(CustomException $e) {
            return response()->json(['message' => 'ユーザーのサウナログの取得に失敗しました。'], 400);
        }
    }

    //特定のサウナログの表示
    public function getLogById($id)
    {
        try {
            $log = $this->saunalogService->getLogById($id);
            if (!$log) {
                return response()->json(['message' => '指定されたサウナログが見つかりません。'], 404);
            } 
            return response()->json($log);   
        } catch(CustomException $e) {
            return response()->json(['message' => '特定のサウナログの取得に失敗しました。'], 400);
        }
    }

    //サウナログの新規作成
    public function create(CreateSaunalogRequest $request)
    {
        try {
            $user = $request->user();
            return $this->saunalogService->createLog($request->validated(), $user);
        } catch(CustomException $e) {
            return response()->json(['message' => 'サウナログの作成に失敗しました。'], 400);
        }
        
    }

    //特定のサウナログの編集
    public function update($id, UpdateSaunalogRequest $request)
    {
        try {
            return $this->saunalogService->updateLogById($id, $request->validated());
        } catch(CustomException $e) {
            return response()->json(['message' => 'サウナログの編集に失敗しました。'], 400);
        }
    }

    //特定のサウナログの削除
    public function delete($id)
    {
        try {
            return $this->saunalogService->deleteLogById($id);
        } catch(CustomException $e) {
            return response()->json(['message' => 'サウナログの削除中にエラーが発生しました。'], 400);
        }
    }
}
