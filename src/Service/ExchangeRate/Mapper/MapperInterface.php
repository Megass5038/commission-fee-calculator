<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\ExchangeRate\Mapper;

use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRateInterface;

/**
 * Helper.
 * Mapper for convert data from raw array to ExchangeRateEntity.
 */
interface MapperInterface
{
    public function mapDataToExchangeRate(array $data): ExchangeRateInterface;
}
