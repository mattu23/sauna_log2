<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected $message = '入力内容に誤りがあります。内容をご確認ください';
    protected $code = 400;
}

