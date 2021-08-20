<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Service\Input\Mapper\CSV;

use PHPUnit\Framework\TestCase;
use Carbon\CarbonImmutable;
use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\User;
use Kalashnik\CommissionTask\Entity\Value\Money;
use Kalashnik\CommissionTask\Exception\Validation\InvalidItemAttributesCountException;
use Kalashnik\CommissionTask\Factory\Config\ConfigFactory;
use Kalashnik\CommissionTask\Service\Input\Mapper\CSV\Mapper;
use Kalashnik\CommissionTask\Validation\Validator\OperationValidator;
use Kalashnik\CommissionTask\Validation\Validator\UserValidator;

class MapperTest extends TestCase
{
    private Mapper $mapper;

    public function setUp()
    {
        $config = ConfigFactory::getConfig();
        $operationValidator = new OperationValidator($config);
        $userValidator = new UserValidator($config);
        $this->mapper = new Mapper($config, $operationValidator, $userValidator);
    }

    /**
     * @param array $item
     * @param Operation $expectedOperation
     * @dataProvider dataProviderForTestMapItemToOperation
     */
    public function testMapItemToOperation(array $item, Operation $expectedOperation)
    {
        $actualOperation = $this->mapper->mapItemToOperation($item);
        $this->assertTrue(
            $actualOperation->equalTo($expectedOperation)
        );
    }

    /**
     * @param array $item
     * @dataProvider dataProviderForTestInvalidItemAttributesCountException
     */
    public function testInvalidItemAttributesCountException(array $item)
    {
        $this->expectException(InvalidItemAttributesCountException::class);
        $this->mapper->mapItemToOperation($item);
    }

    public function dataProviderForTestMapItemToOperation(): array
    {
        return [
            'withdraw operation 1' => [
                [
                    '2014-12-31',
                    '4',
                    'private',
                    'withdraw',
                    '1200.00',
                    'EUR',
                ],
                new Operation(
                    CarbonImmutable::parse('2014-12-31'),
                    new User(4, 'private'),
                    'withdraw',
                    new Money('1200.00', 'EUR')
                ),
            ],
            'withdraw operation 2' => [
                [
                    '2016-12-31',
                    '4',
                    'private',
                    'withdraw',
                    '25.25',
                    'EUR',
                ],
                new Operation(
                    CarbonImmutable::parse( '2016-12-31'),
                    new User(4, 'private'),
                    'withdraw',
                    new Money('25.25', 'EUR')
                ),
            ],
            'deposit operation 1' => [
                [
                    '2017-12-03',
                    '4',
                    'business',
                    'deposit',
                    '41.25',
                    'EUR',
                ],
                new Operation(
                    CarbonImmutable::parse( '2017-12-03'),
                    new User(4, 'business'),
                    'deposit',
                    new Money('41.25', 'EUR')
                ),
            ],
        ];
    }

    public function dataProviderForTestInvalidItemAttributesCountException(): array
    {
        return [
            'error operation 1' => [
                [
                    '2017-12-03',
                    '4',
                    'business',
                    'deposit',
                    '41.25',
                ],
            ],
            'error operation 2' => [
                [
                    '2017-12-03',
                    '4',
                    'business',
                    '41.25',
                    'EUR',
                ],
            ],
        ];
    }
}
