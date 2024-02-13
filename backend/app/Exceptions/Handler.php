<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // カスタム例外のハンドリング
        if ($exception instanceof \App\Exceptions\AuthenticationException) {
            return response()->json(['message' => $exception->getMessage()], 401);
        } elseif ($exception instanceof \App\Exceptions\NotFoundException) {
            return response()->json(['message' => $exception->getMessage()], 404);
        }
        // その他の例外に対するデフォルトの処理を維持
        return parent::render($request, $exception);
    }

}
