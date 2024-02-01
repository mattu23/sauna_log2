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

    //ユーザーに紐づくサウナログ一覧の表示
    public function getLogs(Request $request)
    {
        $userId = $request->user()->id;
        return $this->saunalogService->getLogsByUser($userId);
    }

    //特定のサウナログの表示
    public function getLogById($id)
    {
        return $this->saunalogService->getLogById($id);
    }

    //サウナログの新規作成
    public function create(CreateSaunalogRequest $request)
    {
        $user = $request->user();
        return $this->saunalogService->createLog($request->validated(), $user);
    }

    //特定のサウナログの編集
    public function update($id, UpdateSaunalogRequest $request)
    {
        return $this->saunalogService->updateLogById($id, $request->validated());
    }

    //特定のサウナログの削除
    public function delete($id)
    {
        return $this->saunalogService->deleteLogById($id);
    }
}
