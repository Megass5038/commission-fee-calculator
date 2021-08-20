<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Formatter;

use Kalashnik\CommissionTask\Entity\Value\Money;

/**
 * Provides method for formatting the amount of commission.
 */
interface FormatterInterface
{
    public function formatCommission(Money $money): string;
}
