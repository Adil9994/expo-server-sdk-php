<?php

namespace ExpoSDK\Exceptions;

use Throwable;

class ExpoException extends \Exception
{
    protected $details;

    public function __construct($message = "", $code = 0, $details = [], Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->details = $details;
    }
}
