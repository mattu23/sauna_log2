<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
    

    //すべてのサウナログをページネーションを使用し一覧表示
    public function getAllLogs(Request $request) 
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 5);

        $saunalogs = Saunalog::with('user:id,username')->paginate($limit, ['*'], 'page', $page);


        return response()->json([
            'logs' => $saunalogs->items(),
            'totalPages' => $saunalogs->lastPage(),
            'currentPage' => $saunalogs->currentPage(),
        ]);
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
        // $saunalog = Saunalog::findOrFail($id); // IDを使ってサウナログのインスタンスを取得
        // $this->authorize('update', $saunalog); 
        try {
            return $this->saunalogService->updateLogById($id, $request->validated());
        } catch(NotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }


    //特定のサウナログの削除
    public function delete($id)
    {
        // $saunalog = Saunalog::findOrFail($id);
        // $this->authorize('delete', $saunalog);
        try {
            return $this->saunalogService->deleteLogById($id);
        } catch(NotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }


    //サウナログのCSV出力
    public function csvDownload()
    {
        $user = Auth::user();
        $fileName = 'saunalogs.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma" => "no-cache",
            "Cache-Control" => "no-store, no-cache, must-revalidate",
            "Expires" => "0"
        ];

        $columns = ['Log Id', 'name', 'area', 'rank', 'Comment', 'Username'];

        $callback = function() use ($user, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_map(function($col) {
                return mb_convert_encoding($col, 'SJIS-win', 'UTF-8');
            }, $columns));

            foreach ($user->saunalogs as $log) {
                $row = [
                    $log->id,
                    $log->name,
                    $log->area,
                    $log->rank,
                    $log->comment,
                    $user->username,
                ];
                fputcsv($file, array_map(function($cell) {
                    return mb_convert_encoding($cell, 'SJIS-win', 'UTF-8');
                }, $row));
            }
            fclose($file);
        };
        return new StreamedResponse($callback, 200, $headers);
    }
}
