<?php

namespace App\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
    protected $message = 'ユーザー権限がありません。';
    protected $code = 403;
}
