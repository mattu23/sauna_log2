<?php

namespace App\Exceptions;

use Exception;

class InvalidPasswordException extends Exception
{
    protected $message = '認証に失敗しました。無効なパスワードです。';
    protected $code = 401;
}
