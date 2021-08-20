<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Exception\ExchangeRate\ExchangeRateAPI;

use Kalashnik\CommissionTask\Exception\CommissionFeeException;
use Throwable;

class NotExpectedResponseException extends CommissionFeeException
{
    private string $requestPath;
    private string $responseContent;
    private int $httpStatusCode;

    public function __construct(
        string $message,
        string $requestPath,
        string $responseContent,
        int $httpStatusCode,
        int $code = 0,
        Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->requestPath = $requestPath;
        $this->responseContent = $responseContent;
        $this->httpStatusCode = $httpStatusCode;
    }

    public function getRequestPath(): string
    {
        return $this->requestPath;
    }

    public function getResponseContent(): string
    {
        return $this->responseContent;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
