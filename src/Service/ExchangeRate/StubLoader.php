<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\ExchangeRate;

use Illuminate\Contracts\Config\Repository as ConfigContract;
use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRate;
use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRateInterface;

/**
 * Provides ExchangeRateEntity with stub rates.
 */
class StubLoader implements LoaderInterface
{
    private ConfigContract $config;

    public function __construct(ConfigContract $config)
    {
        $this->config = $config;
    }

    public function loadAndGetExchangeRate(): ExchangeRateInterface
    {
        $stubRates = $this->config->get('exchange_rate.providers.stub.rates');
        return new ExchangeRate(
            $stubRates,
            $this->config->get('currency.base'),
            time()
        );
    }
}
