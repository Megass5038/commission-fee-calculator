<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\ExchangeRate\Mapper;

use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRate;
use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRateInterface;

class ExchangeRateAPIMapper implements MapperInterface
{
    /**
     * @param array $data format: ["timestamp": int, "base": str, "rates": array[string]float]
     * @return ExchangeRate
     */
    public function mapDataToExchangeRate(array $data): ExchangeRateInterface
    {
        return new ExchangeRate(
            $data["rates"],
            $data["base"],
            (int) $data["timestamp"],
        );
    }
}
