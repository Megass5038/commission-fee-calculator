<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Repository\Operation;

use PHPUnit\Framework\TestCase;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\User;
use Kalashnik\CommissionTask\Entity\Value\Money;
use Kalashnik\CommissionTask\Repository\Operation\FromArray;

class FromArrayTest extends TestCase
{
    private static ?FromArray $repository = null;

    public static function setUpBeforeClass()
    {
        static::$repository = new FromArray();
    }

    public static function tearDownAfterClass()
    {
        static::$repository = null;
    }

    /**
     * @param Operation $operation
     * @dataProvider dataProviderForTestSave
     */
    public function testSave(Operation $operation)
    {
        $this->assertNull(
            static::$repository->save($operation)
        );
    }

    /**
     * @depends testSave
     */
    public function testGetUserWithdrawOperations()
    {
        $operations = static::$repository->getUserWithdrawOperationsForDatePeriod(
            new User(1, 'private'), CarbonPeriod::create('2021-08-02', '2021-08-08')
        );
        $this->assertSame(
            count($operations), 2
        );
    }

    public function dataProviderForTestSave(): array
    {
        return [
            [
                new Operation(
                    CarbonImmutable::parse('2021-08-02'), new User(1, 'private'),
                    'withdraw', new Money('25', 'USD')
                ),
            ],
            [
                new Operation(
                    CarbonImmutable::parse('2021-08-03'), new User(1, 'private'),
                    'withdraw', new Money('35', 'USD'),
                ),
            ],
            [
                new Operation(
                    CarbonImmutable::parse('2021-08-04'), new User(2, 'private'),
                    'withdraw', new Money('36', 'USD'),
                ),
            ],
            [
                new Operation(
                    CarbonImmutable::parse('2021-08-15'), new User(1, 'private'),
                    'withdraw', new Money('36', 'USD'),
                ),
            ],
        ];
    }
}
