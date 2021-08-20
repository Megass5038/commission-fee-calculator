<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Service\Calculator;

use PHPUnit\Framework\TestCase;
use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRate;
use Kalashnik\CommissionTask\Entity\Value\Money as MoneyEntity;
use Kalashnik\CommissionTask\Service\Calculator\Money as MoneyCalculator;
use Kalashnik\CommissionTask\Service\Math\Math;

class MoneyCalculatorTest extends TestCase
{
    private MoneyCalculator $moneyCalculator;

    public function setUp()
    {
        $this->moneyCalculator = new MoneyCalculator(
            new Math(6), $this->getStubExchangeRate()
        );
    }

    protected function getStubExchangeRate()
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
     * @param MoneyEntity $leftMoney
     * @param MoneyEntity $rightMoney
     * @param MoneyEntity $expectedMoney
     * @dataProvider dataProviderForTestAdd
     */
    public function testAdd(MoneyEntity $leftMoney, MoneyEntity $rightMoney, MoneyEntity $expectedMoney)
    {
        $actualMoney = $this->moneyCalculator->add($leftMoney, $rightMoney);
        $this->assertTrue(
            $actualMoney->equalTo($expectedMoney)
        );
    }

    /**
     * @param MoneyEntity $leftMoney
     * @param MoneyEntity $rightMoney
     * @param MoneyEntity $expectedMoney
     * @dataProvider dataProviderForTestAdd
     */
    public function testSum(MoneyEntity $leftMoney, MoneyEntity $rightMoney, MoneyEntity $expectedMoney)
    {
        $actualMoney = $this->moneyCalculator->sum($leftMoney, $rightMoney);
        $this->assertTrue(
            $actualMoney->equalTo($expectedMoney)
        );
    }

    /**
     * @param MoneyEntity $leftMoney
     * @param MoneyEntity $rightMoney
     * @param MoneyEntity $expectedMoney
     * @dataProvider dataProviderForTestAdd
     */
    public function testSub(MoneyEntity $leftMoney, MoneyEntity $rightMoney, MoneyEntity $expectedMoney)
    {
        $actualMoney = $this->moneyCalculator->add($leftMoney, $rightMoney);
        $this->assertTrue(
            $actualMoney->equalTo($expectedMoney)
        );
    }

    /**
     * @param MoneyEntity $leftMoney
     * @param MoneyEntity $rightMoney
     * @param bool $expectedResult
     * @dataProvider dataProviderForTestGt
     */
    public function testGt(MoneyEntity $leftMoney, MoneyEntity $rightMoney, bool $expectedResult)
    {
        $actualResult = $this->moneyCalculator->gt($leftMoney, $rightMoney);
        $this->assertTrue(
            $actualResult === $expectedResult
        );
    }

    /**
     * @param MoneyEntity $leftMoney
     * @param MoneyEntity $rightMoney
     * @param bool $expectedResult
     * @dataProvider dataProviderForTestGte
     */
    public function testGte(MoneyEntity $leftMoney, MoneyEntity $rightMoney, bool $expectedResult)
    {
        $actualResult = $this->moneyCalculator->gte($leftMoney, $rightMoney);
        $this->assertTrue(
            $actualResult === $expectedResult
        );
    }

    /**
     * @param MoneyEntity $leftMoney
     * @param bool $expectedResult
     * @dataProvider dataProviderForTestGtZero
     */
    public function testGtZero(MoneyEntity $leftMoney, bool $expectedResult)
    {
        $actualResult = $this->moneyCalculator->gtZero($leftMoney);
        $this->assertTrue(
            $actualResult === $expectedResult
        );
    }

    /**
     * @param MoneyEntity $leftMoney
     * @param string $percent
     * @param MoneyEntity $expectedMoney
     * @dataProvider dataProviderForTestPercentOfAmount
     */
    public function testPercentOfAmount(MoneyEntity $leftMoney, string $percent, MoneyEntity $expectedMoney)
    {
        $actualMoney = $this->moneyCalculator->percentOfAmount($leftMoney, $percent);
        $this->assertTrue(
            $actualMoney->equalTo($expectedMoney)
        );
    }

    /**
     * @param MoneyEntity $leftMoney
     * @param MoneyEntity $expectedMoney
     * @dataProvider dataProviderForTestConvertToBaseCurrency
     */
    public function testConvertToBaseCurrency(MoneyEntity $leftMoney, MoneyEntity $expectedMoney)
    {
        $actualMoney = $this->moneyCalculator->convertMoneyToBaseCurrency($leftMoney);
        $this->assertTrue(
            $actualMoney->equalTo($expectedMoney)
        );
    }

    /**
     * @param MoneyEntity $leftMoney
     * @param MoneyEntity $expectedMoney
     * @dataProvider dataProviderForTestConvertFromBaseCurrency
     */
    public function testConvertFromBaseCurrency(MoneyEntity $leftMoney, MoneyEntity $expectedMoney)
    {
        $actualMoney = $this->moneyCalculator->convertMoneyFromBaseCurrency($leftMoney, $expectedMoney->getCurrency());
        $this->assertTrue(
            $actualMoney->equalTo($expectedMoney)
        );
    }

    public function dataProviderForTestAdd(): array
    {
        return [
            'usd add usd' => [
                new MoneyEntity('25.00', 'USD'),
                new MoneyEntity('25.00', 'USD'),
                new MoneyEntity('50.000000', 'USD'),
            ],
            'jpy add jpy' => [
                new MoneyEntity('25.25', 'JPY'),
                new MoneyEntity('25.25', 'JPY'),
                new MoneyEntity('50.500000', 'JPY'),
            ],
            'usd add eur' => [
                new MoneyEntity('25.25', 'USD'),
                new MoneyEntity('25.25', 'EUR'),
                new MoneyEntity('47.212251', 'EUR'),
            ]
        ];
    }

    public function dataProviderForTestSub(): array
    {
        return [
            'usd sub usd' => [
                new MoneyEntity('50.00', 'USD'),
                new MoneyEntity('25.00', 'USD'),
                new MoneyEntity('25.000000', 'USD'),
            ],
            'jpy sub jpy' => [
                new MoneyEntity('50.25', 'JPY'),
                new MoneyEntity('25.00', 'JPY'),
                new MoneyEntity('25.250000', 'JPY'),
            ],
            'usd sub eur' => [
                new MoneyEntity('58.25', 'USD'),
                new MoneyEntity('25.25', 'EUR'),
                new MoneyEntity('25.415390', 'EUR'),
            ]
        ];
    }

    public function dataProviderForTestGt(): array
    {
        return [
            'usd == usd' => [
                new MoneyEntity('50.00', 'USD'),
                new MoneyEntity('50.00', 'USD'),
                false,
            ],
            'jpy > jpy' => [
                new MoneyEntity('50.25', 'JPY'),
                new MoneyEntity('25.00', 'JPY'),
                true,
            ],
            'usd < eur' => [
                new MoneyEntity('16.34', 'USD'),
                new MoneyEntity('15', 'EUR'),
                false
            ]
        ];
    }

    public function dataProviderForTestGte(): array
    {
        return [
            'usd == usd' => [
                new MoneyEntity('50.00', 'USD'),
                new MoneyEntity('50.00', 'USD'),
                true,
            ],
            'jpy > jpy' => [
                new MoneyEntity('50.25', 'JPY'),
                new MoneyEntity('25.00', 'JPY'),
                true,
            ],
            'usd < eur' => [
                new MoneyEntity('16.34', 'USD'),
                new MoneyEntity('15', 'EUR'),
                false
            ]
        ];
    }

    public function dataProviderForTestGtZero(): array
    {
        return [
            'usd > 0' => [
                new MoneyEntity('50.00', 'USD'),
                true,
            ],
            'jpy < 0' => [
                new MoneyEntity('-1.25', 'JPY'),
                false,
            ],
            'eur == 0' => [
                new MoneyEntity('0.00', 'EUR'),
                false,
            ],
        ];
    }

    public function dataProviderForTestPercentOfAmount(): array
    {
        return [
            'usd' => [
                new MoneyEntity('50.00', 'USD'),
                '0.5',
                new MoneyEntity('0.250000', 'USD'),
            ],
            'jpy' => [
                new MoneyEntity('25000', 'JPY'),
                '0.03',
                new MoneyEntity('7.500000', 'JPY'),
            ],
        ];
    }

    public function dataProviderForTestConvertToBaseCurrency(): array
    {
        return [
            'usd' => [
                new MoneyEntity('25000', 'USD'),
                new MoneyEntity('21744.802992', 'EUR'),
            ],
            'jpy' => [
                new MoneyEntity('25000', 'JPY'),
                new MoneyEntity('193.005481', 'EUR'),
            ],
        ];
    }

    public function dataProviderForTestConvertFromBaseCurrency(): array
    {
        return [
            'usd' => [
                new MoneyEntity('21744.802992', 'EUR'),
                new MoneyEntity('24999.999999', 'USD'),
            ],
            'jpy' => [
                new MoneyEntity('193.005481', 'EUR'),
                new MoneyEntity('24999.999953', 'JPY'),
            ],
        ];
    }
}
