<?php

namespace App\Exceptions;

use Exception;

class SystemException extends Exception
{
    protected $message = '内部システムエラーが発生しました。';
    protected $code = 500;
}
