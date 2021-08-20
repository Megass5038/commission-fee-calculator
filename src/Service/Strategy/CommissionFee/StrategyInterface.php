<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Strategy\CommissionFee;

use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\Value\Money;

interface StrategyInterface
{
    public function calculateCommissionFeeForOperation(Operation $operation): Money;
}
