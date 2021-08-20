<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Illuminate\Config\Repository as ConfigRepository;
use Kalashnik\CommissionTask\Exception\Validation\ValidationException;
use Kalashnik\CommissionTask\Validation\Validator\OperationValidator;

class OperationValidatorTest extends TestCase
{
    private OperationValidator $operationValidator;

    public function setUp()
    {
        $config = new ConfigRepository([]);
        $this->operationValidator = new OperationValidator($config);
    }

    /**
     * @param string $date
     * @param string $type
     * @param string $amount
     * @param string $currency
     * @dataProvider dataProviderForTestValidate
     */
    public function testValidate(string $date, string $type, string $amount, string $currency)
    {
        $this->assertNull(
            $this->operationValidator->validate([
                'date' => $date,
                'type' => $type,
                'amount' => $amount,
                'currency' => $currency,
            ])
        );
    }

    /**
     * @param string $date
     * @param string $type
     * @param string $amount
     * @param string $currency
     * @dataProvider dataProviderForTestValidateDateException
     */
    public function testValidateDateException(string $date, string $type, string $amount, string $currency)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessageRegExp('/"date" with value/');

        $this->assertNull(
            $this->operationValidator->validate([
                'date' => $date,
                'type' => $type,
                'amount' => $amount,
                'currency' => $currency,
            ])
        );
    }

    /**
     * @param string $date
     * @param string $type
     * @param string $amount
     * @param string $currency
     * @dataProvider dataProviderForTestValidateTypeException
     */
    public function testValidateTypeException(string $date, string $type, string $amount, string $currency)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessageRegExp('/"type" with value/');

        $this->assertNull(
            $this->operationValidator->validate([
                'date' => $date,
                'type' => $type,
                'amount' => $amount,
                'currency' => $currency,
            ])
        );
    }

    /**
     * @param string $date
     * @param string $type
     * @param string $amount
     * @param string $currency
     * @dataProvider dataProviderForTestValidateAmountException
     */
    public function testValidateAmountException(string $date, string $type, string $amount, string $currency)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessageRegExp('/"amount" with value/');

        $this->assertNull(
            $this->operationValidator->validate([
                'date' => $date,
                'type' => $type,
                'amount' => $amount,
                'currency' => $currency,
            ])
        );
    }

    /**
     * @param string $date
     * @param string $type
     * @param string $amount
     * @param string $currency
     * @dataProvider dataProviderForTestValidateCurrencyException
     */
    public function testValidateCurrencyException(string $date, string $type, string $amount, string $currency)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessageRegExp('/"currency" with value/');

        $this->assertNull(
            $this->operationValidator->validate([
                'date' => $date,
                'type' => $type,
                'amount' => $amount,
                'currency' => $currency,
            ])
        );
    }

    public function dataProviderForTestValidate()
    {
        return [
            'operation 1' => [
                '2020-03-04',
                'deposit',
                '25.25',
                'EUR',
            ],
            'operation 2' => [
                '2019-03-04',
                'withdraw',
                25.25,
                'USD',
            ],
        ];
    }

    public function dataProviderForTestValidateDateException()
    {
        return [
            'operation 1 with missed date' => [
                '',
                'deposit',
                '25.25',
                'EUR',
            ],
            'operation 2 with invalid format' => [
                '2019/03/04',
                'withdraw',
                '25.25',
                'USD',
            ],
        ];
    }

    public function dataProviderForTestValidateTypeException()
    {
        return [
            'operation 1 with missed type' => [
                '2021-01-01',
                '',
                '25.25',
                'EUR',
            ],
            'operation 2 with invalid type' => [
                '2019-03-04',
                'invalid',
                '25.25',
                'USD',
            ],
        ];
    }

    public function dataProviderForTestValidateAmountException()
    {
        return [
            'operation 1 with missed amount' => [
                '2020-03-04',
                'deposit',
                '',
                'EUR',
            ],
            'operation 2 with negative amount' => [
                '2019-03-04',
                'withdraw',
                '-25.25',
                'USD',
            ],
        ];
    }

    public function dataProviderForTestValidateCurrencyException()
    {
        return [
            'operation 1 with missed currency' => [
                '2020-03-04',
                'deposit',
                '25.1',
                '',
            ],
        ];
    }
}
