<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Feature\Strategy\CommissionFee;

use PHPUnit\Framework\TestCase;
use Carbon\CarbonImmutable;
use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\User;
use Kalashnik\CommissionTask\Entity\Value\Money;
use Kalashnik\CommissionTask\Factory\Config\ConfigFactory;
use Kalashnik\CommissionTask\Service\Math\Math;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\SimplePercent;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\Withdraw\WithSpentAmount;
use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRate;
use Kalashnik\CommissionTask\Repository\Operation\FromArray;
use Kalashnik\CommissionTask\Service\Calculator\Money as MoneyCalculator;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\Chooser\Chooser as StrategyChooser;

class ChooserTest extends TestCase
{
    private FromArray $operationsRepository;
    private StrategyChooser $strategyChooser;

    public function setUp()
    {
        $this->operationsRepository = new FromArray();
        $moneyCalculator = new MoneyCalculator(new Math(6), $this->getStubExchangeRate());
        $this->strategyChooser = new StrategyChooser(
            ConfigFactory::getConfig(),
            $this->operationsRepository,
            $moneyCalculator
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
     * @param string $expectedClass
     * @dataProvider dataProviderForTestDepositOperation
     */
    public function testDepositOperation(Operation $operation, string $expectedStrategy)
    {
        $actualStrategy = $this->strategyChooser->chooseCommissionStrategyForOperation($operation);
        $this->assertInstanceOf($expectedStrategy, $actualStrategy);
    }

    /**
     * @param array<Operation> $previousOperations
     * @param Operation $operation
     * @param string $expectedStrategy
     * @throws \Kalashnik\CommissionTask\Exception\Strategy\CommissionFee\InvalidOperationTypeException
     * @dataProvider dataProviderForTestWithdrawOperation
     */
    public function testWithdrawOperation(
        array $previousOperations,
        Operation $operation, string $expectedStrategy
    )
    {
        foreach ($previousOperations as $previousOperation) {
            $this->operationsRepository->save($previousOperation);
        }
        $actualStrategy = $this->strategyChooser->chooseCommissionStrategyForOperation($operation);
        $this->assertInstanceOf($expectedStrategy, $actualStrategy);
    }

    /**
     * @param array $previousOperations
     * @param Operation $operation
     * @param string $expectedStrategy
     * @param Money $spentAmount
     * @dataProvider dataProviderForTestWithdrawWithSpentAmountOperation
     */
    public function testWithdrawWithSpentAmount(
        array $previousOperations,
        Operation $operation,
        string $expectedStrategy,
        Money $spentAmount
    )
    {
        foreach ($previousOperations as $previousOperation) {
            $this->operationsRepository->save($previousOperation);
        }
        $actualStrategy = $this->strategyChooser->chooseCommissionStrategyForOperation($operation);
        $this->assertInstanceOf($expectedStrategy, $actualStrategy);
        /** @var Money $actualSpentAmount */
        $actualSpentAmount = $actualStrategy->getSpentAmount();
        $this->assertTrue(
            $actualSpentAmount->equalTo($spentAmount)
        );
    }

    public function dataProviderForTestDepositOperation(): array
    {
        return [
            [
                new Operation(
                    CarbonImmutable::parse('2021-08-01'),
                    new User(1, 'private'),
                    'deposit',
                    new Money('25.00', 'USD')
                ),
                SimplePercent::class,
            ],
            [
                new Operation(
                    CarbonImmutable::parse('2021-08-01'),
                    new User(2, 'business'),
                    'deposit',
                    new Money('25.00', 'USD')
                ),
                SimplePercent::class,
            ]
        ];
    }

    public function dataProviderForTestWithdrawOperation(): array
    {
        return [
            [
                [
                    new Operation(
                        CarbonImmutable::parse('2021-08-02'),
                        new User(1, 'private'),
                        'withdraw',
                        new Money('25.00', 'USD')
                    ),
                    new Operation(
                        CarbonImmutable::parse('2021-08-03'),
                        new User(1, 'private'),
                        'withdraw',
                        new Money('25.00', 'USD')
                    ),
                    new Operation(
                        CarbonImmutable::parse('2021-08-04'),
                        new User(1, 'private'),
                        'withdraw',
                        new Money('25.00', 'USD')
                    ),
                ],
                new Operation(
                    CarbonImmutable::parse('2021-08-05'),
                    new User(1, 'private'),
                    'withdraw',
                    new Money('25.00', 'USD')
                ),
                SimplePercent::class,
            ],
            [
                [
                ],
                new Operation(
                    CarbonImmutable::parse('2021-08-05'),
                    new User(2, 'business'),
                    'withdraw',
                    new Money('25.00', 'USD')
                ),
                SimplePercent::class,
            ],
        ];
    }

    public function dataProviderForTestWithdrawWithSpentAmountOperation(): array
    {
        return [
            [
                [
                    new Operation(
                        CarbonImmutable::parse('2021-08-02'),
                        new User(1, 'private'),
                        'withdraw',
                        new Money('25.00', 'USD')
                    ),
                    new Operation(
                        CarbonImmutable::parse('2021-08-03'),
                        new User(1, 'private'),
                        'withdraw',
                        new Money('25.00', 'USD')
                    ),
                ],
                new Operation(
                    CarbonImmutable::parse('2021-08-05'),
                    new User(1, 'private'),
                    'withdraw',
                    new Money('25.00', 'USD')
                ),
                WithSpentAmount::class,
                new Money('50.000000', 'USD'),
            ],
        ];
    }
}
