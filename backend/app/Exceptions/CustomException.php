<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    public function __construct($message = "処理中にエラーが発生しました。", $code = 401, Exception $previous = null)
      {
          parent::__construct($message, $code, $previous);
      }
}
