<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Service\Strategy\Withdraw;

use PHPUnit\Framework\TestCase;
use Carbon\CarbonImmutable;
use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRate;
use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\User;
use Kalashnik\CommissionTask\Entity\Value\Money as MoneyEntity;
use Kalashnik\CommissionTask\Service\Calculator\Money as MoneyCalculator;
use Kalashnik\CommissionTask\Service\Math\Math;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\Withdraw\WithSpentAmount;

class WithSpentAmountTest extends TestCase
{
    private WithSpentAmount $withNotSpentLimit;
    private WithSpentAmount $withPartialSpentLimit;
    private WithSpentAmount $withTotallySpentLimit;

    public function setUp()
    {
        $math = new Math(6);
        $moneyCalculator = new MoneyCalculator($math, $this->getStubExchangeRate());
        $this->withNotSpentLimit = new WithSpentAmount(
            $moneyCalculator,
            new MoneyEntity('0', 'EUR'),
            new MoneyEntity('1000.00', 'EUR'),
            '0.03'
        );
        $this->withPartialSpentLimit = new WithSpentAmount(
            $moneyCalculator,
            new MoneyEntity('500.00', 'EUR'),
            new MoneyEntity('1000.00', 'EUR'),
            '0.03'
        );
        $this->withTotallySpentLimit = new WithSpentAmount(
            $moneyCalculator,
            new MoneyEntity('1000.00', 'EUR'),
            new MoneyEntity('1000.00', 'EUR'),
            '0.03'
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
     * @dataProvider dataProviderForTestNotSpentLimitCommission
     */
    public function testNotSpentLimitCommission(Operation $operation, MoneyEntity $expectedMoney)
    {
        $actualMoney = $this->withNotSpentLimit->calculateCommissionFeeForOperation($operation);
        $this->assertTrue(
            $actualMoney->equalTo($expectedMoney)
        );
    }

    /**
     * @param Operation $operation
     * @param MoneyEntity $expectedMoney
     * @dataProvider dataProviderForTestPartialSpentLimitCommission
     */
    public function testPartialSpentLimitCommission(Operation $operation, MoneyEntity $expectedMoney)
    {
        $actualMoney = $this->withPartialSpentLimit->calculateCommissionFeeForOperation($operation);
        $this->assertTrue(
            $actualMoney->equalTo($expectedMoney)
        );
    }

    /**
     * @param Operation $operation
     * @param MoneyEntity $expectedMoney
     * @dataProvider dataProviderForTestTotallySpentLimitCommission
     */
    public function testTotallySpentLimitCommission(Operation $operation, MoneyEntity $expectedMoney)
    {
        $actualMoney = $this->withTotallySpentLimit->calculateCommissionFeeForOperation($operation);
        $this->assertTrue(
            $actualMoney->equalTo($expectedMoney)
        );
    }

    public function dataProviderForTestNotSpentLimitCommission(): array
    {
        return [
            'usd' => [
                new Operation(
                    CarbonImmutable::parse('2021-01-01'), new User(5, 'private'),
                    'withdraw', new MoneyEntity('250', 'USD')
                ),
                new MoneyEntity('0.00', 'USD'),
            ],
            'eur' => [
                new Operation(
                    CarbonImmutable::parse('2021-01-01'), new User(5, 'private'),
                    'withdraw', new MoneyEntity('1500', 'EUR')
                ),
                new MoneyEntity('0.150000', 'EUR'),
            ],
        ];
    }

    public function dataProviderForTestPartialSpentLimitCommission(): array
    {
        return [
            'usd' => [
                new Operation(
                    CarbonImmutable::parse('2021-01-01'), new User(5, 'private'),
                    'withdraw', new MoneyEntity('250', 'USD')
                ),
                new MoneyEntity('0.00', 'USD'),
            ],
            'eur' => [
                new Operation(
                    CarbonImmutable::parse('2021-01-01'), new User(5, 'private'),
                    'withdraw', new MoneyEntity('1500', 'EUR')
                ),
                new MoneyEntity('0.300000', 'EUR'),
            ],
        ];
    }

    public function dataProviderForTestTotallySpentLimitCommission(): array
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
