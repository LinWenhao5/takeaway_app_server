<?php

namespace App\Exceptions;

use Exception;

class ProductNotAvailableException extends Exception
{
    protected $code = 409;
    protected $message = 'Product is not available';

    public function __construct($message = null, $code = null)
    {
        parent::__construct($message ?? $this->message, $code ?? $this->code);
    }
}
