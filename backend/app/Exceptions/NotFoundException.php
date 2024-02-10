<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $message = 'リソースが見つかりません。';
    protected $code = 404;
}
