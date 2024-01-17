<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaunalogController;
use App\Http\Controllers\AuthController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// SaunalogController ルート
Route::middleware('auth:sanctum')->apiResource('saunalog', SaunalogController::class);

// AuthControllerのルート
Route::controller(AuthController::class)->group(function () {
    // ユーザー登録
    Route::post('/signup', 'signup');

    // ログイン
    Route::post('/signin', 'signin');

    // ログアウト
    Route::post('/logout', 'logout')->middleware('auth:sanctum');

    // ユーザー情報の取得
    Route::get('/getUser', 'getUser');

    // ユーザー情報の更新
    Route::put('/update-user', 'updateUser')->middleware('auth:sanctum');

    // パスワードの更新
    Route::put('/update-password', 'updatePassword')->middleware('auth:sanctum');

    // ユーザーの削除
    Route::delete('/delete-user', 'deleteUser')->middleware('auth:sanctum');
});
