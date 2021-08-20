<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Calculator;

use Illuminate\Contracts\Config\Repository as ConfigContract;
use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\Value\Money;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\Chooser\ChooserInterface;

/**
 * Calculates commission for operation base on strategy which choose StrategyChooser.
 */
class CommissionFee implements CommissionFeeInterface
{
    protected ConfigContract $config;
    protected ChooserInterface $strategyChooser;

    public function __construct(ConfigContract $config, ChooserInterface $strategyChooser)
    {
        $this->config = $config;
        $this->strategyChooser = $strategyChooser;
    }

    public function calculateCommissionFeeForOperation(Operation $operation): Money
    {
        $operationCommissionStrategy = $this->strategyChooser->chooseCommissionStrategyForOperation($operation);
        return $operationCommissionStrategy->calculateCommissionFeeForOperation($operation);
    }
}
