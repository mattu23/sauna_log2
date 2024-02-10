<?php

namespace App\Exceptions;

use Exception;

class AuthenticationException extends Exception
{
    protected $message = '認証に失敗しました。';
    protected $code = 401;
}
