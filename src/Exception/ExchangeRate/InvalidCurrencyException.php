<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Exception\ExchangeRate;

use Kalashnik\CommissionTask\Exception\CommissionFeeException;
use Throwable;

class InvalidCurrencyException extends CommissionFeeException
{
    private string $currency;

    public function __construct(string $message, string $currency, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }
}
