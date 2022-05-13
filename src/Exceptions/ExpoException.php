<?php

namespace ExpoSDK\Exceptions;

use Throwable;

class ExpoException extends \Exception
{
    protected $expoCode;
    protected $details;

    public function __construct($message = "", $statusCode = 0, Throwable $previous = null, $expoCode = null, $details = [])
    {
        parent::__construct($message, $statusCode, $previous);

        $this->expoCode = $expoCode;
        $this->details = $details;
    }

    public function getExpoCode()
    {
        return $this->expoCode;
    }

    public function getDetails()
    {
        return $this->details;
    }
}
