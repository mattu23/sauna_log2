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

    public function index(Request $request)
    {
        $userId = $request->user()->id;
        return $this->saunalogService->getLogsByUser($userId);
    }

    public function show($id)
    {
        return $this->saunalogService->findOne($id);
    }

    public function store(CreateSaunalogRequest $request)
    {
        $user = $request->user();
        return $this->saunalogService->create($request->validated(), $user);
    }

    public function update($id, UpdateSaunalogRequest $request)
    {
        return $this->saunalogService->updateSaunalog($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->saunalogService->delete($id);
    }
}
