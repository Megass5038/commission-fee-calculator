<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Calculator;

use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\Value\Money;

interface CommissionFeeInterface
{
    public function calculateCommissionFeeForOperation(Operation $operation): Money;
}
