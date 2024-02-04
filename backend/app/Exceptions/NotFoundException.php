<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct($message = "対象のデータが見つかりません。", $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
