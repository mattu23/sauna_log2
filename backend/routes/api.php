<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaunalogController;
use App\Http\Controllers\UserController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// UserControllerのルート
Route::controller(UserController::class)->group(function () {

    // ユーザー登録
    Route::post('/signup', 'signUp');

    // ログイン
    Route::post('/signin', 'signIn');

    // ログアウト
    Route::post('/logout', 'logout')->middleware('auth:sanctum');

    // ユーザー情報の取得
    Route::get('/getUser', 'get')->middleware('auth:sanctum');;

    // ユーザー情報の更新
    Route::put('/update-user', 'update')->middleware('auth:sanctum');

    // パスワードの更新
    Route::put('/update-password', 'updatePassword')->middleware('auth:sanctum');

    // ユーザーの削除
    Route::delete('/delete-user', 'delete')->middleware('auth:sanctum');
});


// SaunalogControllerのルート
Route::middleware('auth:sanctum')->group(function () {

    //すべてのサウナログの一覧表示
    Route::get('/saunalog/all', [SaunalogController::class, 'getAllLogs']);

    //ユーザーに紐づくサウナログの一覧の表示
    Route::get('/saunalog', [SaunalogController::class, 'getLogs']);

    //特定のサウナログの表示
    Route::get('/saunalog/{id}', [SaunalogController::class, 'getLogById']);

    //サウナログの新規作成
    Route::post('/saunalog', [SaunalogController::class, 'create']);

    //サウナログの編集
    Route::put('/saunalog/{id}', [SaunalogController::class, 'update']);

    //サウナログの削除
    Route::delete('/saunalog/{id}', [SaunalogController::class, 'delete']);
});
