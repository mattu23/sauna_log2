<?php

namespace App\Exceptions;

use Exception;

class InvalidPasswordException extends Exception
{
    public function __construct($message = "無効なパスワードです。", $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
