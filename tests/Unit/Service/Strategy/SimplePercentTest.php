<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Service\Strategy;

use PHPUnit\Framework\TestCase;
use Carbon\CarbonImmutable;
use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRate;
use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\User;
use Kalashnik\CommissionTask\Entity\Value\Money as MoneyEntity;
use Kalashnik\CommissionTask\Service\Calculator\Money as MoneyCalculator;
use Kalashnik\CommissionTask\Service\Math\Math;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\SimplePercent;

class SimplePercentTest extends TestCase
{
    private SimplePercent $simplePercentStrategy;

    public function setUp()
    {
        $math = new Math(6);
        $moneyCalculator = new MoneyCalculator($math, $this->getStubExchangeRate());
        $this->simplePercentStrategy = new SimplePercent(
            $moneyCalculator, '0.03'
        );
    }

    private function getStubExchangeRate(): ExchangeRate
    {
        return new ExchangeRate(
            [
                'USD' => '1.1497',
                'JPY' => '129.53',
            ],
            'EUR',
            null
        );
    }

    /**
     * @param Operation $operation
     * @param MoneyEntity $expectedMoney
     * @dataProvider dataProviderForTestCalculateCommission
     */
    public function testCalculateCommission(Operation $operation, MoneyEntity $expectedMoney)
    {
        $actualMoney = $this->simplePercentStrategy->calculateCommissionFeeForOperation($operation);
        $this->assertTrue(
            $actualMoney->equalTo($expectedMoney)
        );
    }

    public function dataProviderForTestCalculateCommission(): array
    {
        return [
            'usd' => [
                new Operation(
                    CarbonImmutable::parse('2021-01-01'), new User(5, 'private'),
                    'withdraw', new MoneyEntity('250', 'USD')
                ),
                new MoneyEntity('0.075000', 'USD'),
            ],
            'eur' => [
                new Operation(
                    CarbonImmutable::parse('2021-01-01'), new User(5, 'private'),
                    'withdraw', new MoneyEntity('1500', 'EUR')
                ),
                new MoneyEntity('0.450000', 'EUR'),
            ],
        ];
    }
}
