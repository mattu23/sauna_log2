<?php

namespace App\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
    protected $message = '認可に失敗しました。';
    protected $code = 403;
}