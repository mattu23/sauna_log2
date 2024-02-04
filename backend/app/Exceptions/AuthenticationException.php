<?php

namespace App\Exceptions;

use Exception;

class AuthenticationException extends Exception
{
  public function __construct($message = '認証に失敗しました。', $code = 401, Exception $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}