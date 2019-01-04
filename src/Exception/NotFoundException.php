<?php

namespace App\Exception;

class NotFoundException extends \RuntimeException
{
    private const DESCRIPTION = 'Not Found Exception';

    public function __construct($message = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct(trim(self::DESCRIPTION . '. ' . $message), $code, $previous);
    }
}
