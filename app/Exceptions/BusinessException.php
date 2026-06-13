<?php

namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
    protected int $statusCode = 422;
    protected string $errorCode = 'BUSINESS_ERROR';
    protected string $defaultMessage = 'Business logic error';

    public function __construct(
        ?string $message = null,
        ?string $errorCode = null,
        ?int $statusCode = null
    ) {
        parent::__construct($message ?? $this->defaultMessage);

        $this->errorCode = $errorCode ?? $this->errorCode;
        $this->statusCode = $statusCode ?? $this->statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
