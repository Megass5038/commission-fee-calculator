<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Entity\ExchangeRate;

use Kalashnik\CommissionTask\Entity\Value\Money;

/**
 * Provides the exchange rates of currencies.
 * Note: all rates are returned relative to the base currency.
 */
interface ExchangeRateInterface
{
    public function getRate(string $currency): Money;
    public function getBaseCurrency(): string;
}
