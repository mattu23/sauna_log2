<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saunalog;
use App\Models\User;
use App\Http\Requests\CreateSaunalogRequest; // リクエストバリデーションクラス
use App\Http\Requests\UpdateSaunalogRequest; // リクエストバリデーションクラス
use App\Services\SaunalogService; // Saunalogサービスクラス


class SaunalogController extends Controller
{
    private $saunalogService;

    public function __construct(SaunalogService $saunalogService)
    {
        $this->middleware('auth');
        $this->saunalogService = $saunalogService;
    }

    //サウナログ一覧の表示
    public function getUserSaunaLogs(Request $request)
    {
        $userId = $request->user()->id;
        return $this->saunalogService->getLogsByUser($userId);
    }

    //特定のサウナログの表示（編集時fetchで使用）
    public function getSaunalogById($id)
    {
        return $this->saunalogService->findOne($id);
    }

    //サウナログの新規作成
    public function createSaunalog(CreateSaunalogRequest $request)
    {
        $user = $request->user();
        return $this->saunalogService->create($request->validated(), $user);
    }

    //特定のサウナログの編集
    public function updateSaunalogById($id, UpdateSaunalogRequest $request)
    {
        return $this->saunalogService->updateSaunalog($id, $request->validated());
    }

    //特定のサウナログの削除
    public function destroySaunalogById($id)
    {
        return $this->saunalogService->delete($id);
    }
}
