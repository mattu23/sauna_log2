<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saunalog;
use App\Models\User;
use App\Http\Requests\CreateSaunalogRequest; 
use App\Http\Requests\UpdateSaunalogRequest; 
use App\Services\SaunalogService; 
use App\Exceptions\NotFoundException;
use App\Exceptions\SystemException;



class SaunalogController extends Controller
{
    private $saunalogService;

    public function __construct(SaunalogService $saunalogService)
    {
        $this->middleware('auth');
        $this->saunalogService = $saunalogService;
    }

    //ユーザーに紐づくサウナログを一覧で表示させる
    public function getLogs(Request $request)
    {
        $userId = $request->user()->id;
        return $this->saunalogService->getLogsByUser($userId);
    }

    //特定のサウナログのみの表示
    public function getLogById($id)
    {
        try {
            $log = $this->saunalogService->getLogById($id);
            return response()->json($log);   
        } catch(NotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
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
        try {
            return $this->saunalogService->updateLogById($id, $request->validated());
        } catch(NotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    //特定のサウナログの削除
    public function delete($id)
    {
        try {
            return $this->saunalogService->deleteLogById($id);
        } catch(NotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
