<?php

namespace App\Exception;

class ArgumentNotValidException extends \RuntimeException
{
    private const DESCRIPTION = 'Argument Not Valid Exception';

    public function __construct($message = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct(trim(self::DESCRIPTION . '. ' . $message), $code, $previous);
    }
}
