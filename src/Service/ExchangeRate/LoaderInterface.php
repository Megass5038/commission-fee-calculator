<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\ExchangeRate;

use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRateInterface;

/**
 * Helper.
 * Provides method for load ExchangeRateEntity.
 */
interface LoaderInterface
{
    public function loadAndGetExchangeRate(): ExchangeRateInterface;
}
