<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Strategy\CommissionFee;

use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\Value\Money;
use Kalashnik\CommissionTask\Service\Calculator\MoneyInterface as MoneyCalculatorInterface;

/**
 * When calculating the commission, it simply takes a percentage of the operation amount.
 */
class SimplePercent implements StrategyInterface
{
    private MoneyCalculatorInterface $moneyCalculator;
    private string $commissionPercent;

    public function __construct(MoneyCalculatorInterface $moneyCalculator, string $commissionPercent)
    {
        $this->moneyCalculator = $moneyCalculator;
        $this->commissionPercent = $commissionPercent;
    }

    public function calculateCommissionFeeForOperation(Operation $operation): Money
    {
        return $this->moneyCalculator->percentOfAmount($operation->getMoney(), $this->commissionPercent);
    }
}
