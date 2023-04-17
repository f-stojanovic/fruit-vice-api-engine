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
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $this->message = 'FRUITY_VICE API EXCEPTION: ' . $message;
    }
}
