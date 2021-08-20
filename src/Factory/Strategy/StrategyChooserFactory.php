<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Factory\Strategy;

use Kalashnik\CommissionTask\Factory\Config\ConfigFactory;
use Kalashnik\CommissionTask\Repository\Operation\OperationRepositoryInterface;
use Kalashnik\CommissionTask\Service\Calculator\MoneyInterface as MoneyCalculatorInterface;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\Chooser\Chooser;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\Chooser\ChooserInterface;

class StrategyChooserFactory
{
    public static function getStrategyChooser(
        OperationRepositoryInterface $operationRepository,
        MoneyCalculatorInterface $moneyCalculator
    ): ChooserInterface {
        return new Chooser(
            ConfigFactory::getConfig(),
            $operationRepository,
            $moneyCalculator
        );
    }
}
