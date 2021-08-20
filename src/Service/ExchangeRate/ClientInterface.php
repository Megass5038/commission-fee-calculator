<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\ExchangeRate;

/**
 * Provides method to extract exchange rates independently of source.
 */
interface ClientInterface
{
    public function getExchangeRates(): array;
}
