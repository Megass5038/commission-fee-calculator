<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Factory\Calculator;

use Kalashnik\CommissionTask\Factory\Config\ConfigFactory;
use Kalashnik\CommissionTask\Factory\ExchangeRate\LoaderFactory;
use Kalashnik\CommissionTask\Factory\MathFactory;
use Kalashnik\CommissionTask\Service\Calculator\Money;
use Kalashnik\CommissionTask\Service\Calculator\MoneyInterface as MoneyCalculatorInterface;

class MoneyCalculatorFactory
{
    public static function buildMoneyCalculator(): MoneyCalculatorInterface
    {
        $ratesLoader = LoaderFactory::getLoader();
        $exchangeRate = $ratesLoader->loadAndGetExchangeRate();
        $config = ConfigFactory::getConfig();
        $math = MathFactory::getMath($config->get('math.scale'));

        return new Money(
            $math,
            $exchangeRate
        );
    }
}
