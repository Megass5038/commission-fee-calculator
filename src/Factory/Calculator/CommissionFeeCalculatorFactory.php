<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Factory\Calculator;

use Kalashnik\CommissionTask\Factory\Config\ConfigFactory;
use Kalashnik\CommissionTask\Factory\Strategy\StrategyChooserFactory;
use Kalashnik\CommissionTask\Repository\Operation\OperationRepositoryInterface;
use Kalashnik\CommissionTask\Service\Calculator\CommissionFee;
use Kalashnik\CommissionTask\Service\Calculator\CommissionFeeInterface;

class CommissionFeeCalculatorFactory
{
    public static function buildCalculator(OperationRepositoryInterface $operationRepository): CommissionFeeInterface
    {
        $config = ConfigFactory::getConfig();
        $moneyCalculator = MoneyCalculatorFactory::buildMoneyCalculator();
        $strategyChooser = StrategyChooserFactory::getStrategyChooser(
            $operationRepository,
            $moneyCalculator
        );

        return new CommissionFee(
            $config,
            $strategyChooser
        );
    }
}
