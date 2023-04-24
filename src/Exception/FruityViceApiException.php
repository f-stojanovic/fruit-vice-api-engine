<?php

namespace App\Exception;

use Exception;
use Throwable;

class FruityViceApiException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct("FRUITY_VICE API EXCEPTION: " . $message, $code, $previous);
    }
}
