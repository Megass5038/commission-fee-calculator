<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Service\Math;

use PHPUnit\Framework\TestCase;
use DivisionByZeroError;
use Kalashnik\CommissionTask\Exception\Math\NotWellFormedValueException;
use Kalashnik\CommissionTask\Service\Math\Math;

class MathTest extends TestCase
{
    private Math $math;

    public function setUp()
    {
        $this->math = new Math(6);
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForAddTesting
     */
    public function testAdd(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->add($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForNotWellFormedExceptionTesting
     */
    public function testAddStringException(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->expectException(NotWellFormedValueException::class);
        $this->assertEquals(
            $expectation,
            $this->math->add($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForSubTesting
     */
    public function testSub(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->sub($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForNotWellFormedExceptionTesting
     */
    public function testSubStringException(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->expectException(NotWellFormedValueException::class);
        $this->assertEquals(
            $expectation,
            $this->math->sub($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForGtTesting
     */
    public function testGt(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->gt($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForNotWellFormedExceptionTesting
     */
    public function testGtStringException(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->expectException(NotWellFormedValueException::class);
        $this->assertEquals(
            $expectation,
            $this->math->gt($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForGteTesting
     */
    public function testGte(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->gte($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForNotWellFormedExceptionTesting
     */
    public function testGteStringException(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->expectException(NotWellFormedValueException::class);
        $this->assertEquals(
            $expectation,
            $this->math->gte($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForLtTesting
     */
    public function testLt(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->lt($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForNotWellFormedExceptionTesting
     */
    public function testLtStringException(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->expectException(NotWellFormedValueException::class);
        $this->assertEquals(
            $expectation,
            $this->math->lt($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForLteTesting
     */
    public function testLte(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->lte($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForNotWellFormedExceptionTesting
     */
    public function testLteStringException(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->expectException(NotWellFormedValueException::class);
        $this->assertEquals(
            $expectation,
            $this->math->lte($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForEqTesting
     */
    public function testEq(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->eq($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForNotWellFormedExceptionTesting
     */
    public function testEqStringException(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->expectException(NotWellFormedValueException::class);
        $this->assertEquals(
            $expectation,
            $this->math->eq($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForDivTesting
     */
    public function testDiv(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->div($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForNotWellFormedExceptionTesting
     */
    public function testDivStringException(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->expectException(NotWellFormedValueException::class);
        $this->assertEquals(
            $expectation,
            $this->math->div($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderDivZeroExceptionTesting
     */
    public function testDivZeroException(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->expectException(DivisionByZeroError::class);
        $this->assertEquals(
            $expectation,
            $this->math->div($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForMulTesting
     */
    public function testMul(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->mul($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider dataProviderForNotWellFormedExceptionTesting
     */
    public function testMulStringException(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->expectException(NotWellFormedValueException::class);
        $this->assertEquals(
            $expectation,
            $this->math->mul($leftOperand, $rightOperand)
        );
    }

    /**
     * @param string $number
     * @param int $precision
     * @param string $expectation
     *
     * @dataProvider dataProviderForCeilTesting
     */
    public function testCeil(string $number, int $precision, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->ceil($number, $precision)
        );
    }

    /**
     * @param string $number
     * @param int $precision
     * @param string $expectation
     *
     * @dataProvider dataProviderForCeilNotWellFormedExceptionTesting
     */
    public function testCeilStringException(string $number, int $precision, string $expectation)
    {
        $this->expectException(NotWellFormedValueException::class);
        $this->assertEquals(
            $expectation,
            $this->math->ceil($number, $precision)
        );
    }

    /**
     * @param string $number
     * @param string $percent
     * @param string $expectation
     *
     * @dataProvider dataProviderForPercentOfNumberTesting
     */
    public function testPercentOfNumber(string $number, string $percent, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->percentOfNumber($number, $percent)
        );
    }

    /**
     * @param string $number
     * @param string $percent
     * @param string $expectation
     *
     * @dataProvider dataProviderForNotWellFormedExceptionTesting
     */
    public function testPercentOfNumberStringException(string $number, string $percent, string $expectation)
    {
        $this->expectException(NotWellFormedValueException::class);
        $this->assertEquals(
            $expectation,
            $this->math->percentOfNumber($number, $percent)
        );
    }


    public function dataProviderForAddTesting(): array
    {
        return [
            'add 2 natural numbers' => ['1', '2', '3'],
            'add negative number to a positive' => ['-1', '2', '1'],
            'add natural number to a float' => ['1', '1.05123', '2.051230'],
            'add 2 negative integer numbers' => ['-5', '-3', '-8'],
            'add negative and positive integer numbers' => ['-5', '3', '-2'],
            'add numbers with zero' => ['0', '15.253523', '15.253523'],
        ];
    }
    public function dataProviderForNotWellFormedExceptionTesting(): array
    {
        return [
            'natural number and bad string' => ['1', 'asdf', ''],
            'bad string and natural number' => ['asdf', '1', ''],
        ];
    }


    public function dataProviderForSubTesting(): array
    {
        return [
            'sub 2 natural numbers' => ['1', '2', '-1'],
            'sub negative number to a positive' => ['-1', '2', '-3'],
            'sub float from a natural number' => ['1', '1.05123', '-0.051230'],
            'sub 2 negative integer numbers' => ['-5', '-3', '-2'],
            'sub negative and positive integer numbers' => ['-5', '3', '-8'],
            'sub numbers with zero' => ['0', '15.253523', '-15.253523'],
        ];
    }

    public function dataProviderForGtTesting(): array
    {
        return [
            'gt 2 natural numbers' => ['1', '2', false],
            'gt 2 float numbers' => ['1.054', '1.053', true],
            'gt 2 same numbers' => ['2.52', '2.52', false],
        ];
    }

    public function dataProviderForGteTesting(): array
    {
        return [
            'gte 2 natural numbers' => ['1', '2', false],
            'gte 2 float numbers' => ['1.054', '1.053', true],
            'gte 2 same numbers' => ['2.52', '2.52', true],
        ];
    }

    public function dataProviderForLtTesting(): array
    {
        return [
            'lt 2 natural numbers' => ['1', '2', true],
            'lt 2 float numbers' => ['1.054', '1.053', false],
            'lt 2 same numbers' => ['2.52', '2.52', false],
        ];
    }

    public function dataProviderForLteTesting(): array
    {
        return [
            'lt 2 natural numbers' => ['1', '2', true],
            'lt 2 float numbers' => ['1.054', '1.053', false],
            'lt 2 same numbers' => ['2.52', '2.52', true],
        ];
    }

    public function dataProviderForEqTesting(): array
    {
        return [
            'eq 2 natural numbers' => ['1', '2', false],
            'eq 2 float numbers' => ['1.054', '1.053', false],
            'eq 2 same numbers' => ['2.52', '2.52', true],
        ];
    }

    public function dataProviderForDivTesting(): array
    {
        return [
            'div 2 natural numbers' => ['1', '2', '0.5'],
            'div negative number to a positive' => ['-1', '2', '-0.5'],
            'div float number to natural number' => ['1', '1.05123', '0.951266'],
            'div 2 negative integer numbers' => ['-5', '-3', '1.666666'],
            'div negative and positive integer numbers' => ['-5', '3', '-1.666666'],
            'div numbers with zero' => ['0', '15.253523', '0'],
        ];
    }

    public function dataProviderDivZeroExceptionTesting(): array
    {
        return [
          'div zero number' => ['5', '0', '']
        ];
    }

    public function dataProviderForMulTesting(): array
    {
        return [
            'mul 2 natural numbers' => ['1', '2', '2'],
            'mul negative number to a positive' => ['-1', '2', '-2'],
            'mul float number to natural number' => ['1', '1.05123', '1.05123'],
            'mul 2 negative integer numbers' => ['-5', '-3', '15'],
            'mul negative and positive integer numbers' => ['-5', '3', '-15'],
            'mul numbers with zero' => ['0', '15.253523', '0'],
        ];
    }

    public function dataProviderForCeilTesting(): array
    {
        return [
            'ceil with 2 precision' => ['0.023', 2, '0.03'],
            'ceil 0 value' => ['0.00', 0, '0'],
            'ceil with 0 precision' => ['8111.46', 0, '8112'],
        ];
    }

    public function dataProviderForCeilNotWellFormedExceptionTesting(): array
    {
        return [
            'natural number and bad string' => ['asd', 0, ''],
        ];
    }

    public function dataProviderForPercentOfNumberTesting(): array
    {
        return [
            'percent of 100' => ['100', '0.03', '0.03'],
            'percent of 250' => ['250', '0.5', '1.25'],
            'percent of float number' => ['384.967', '15.256', '58.730565'],
            '0 percent of number' => ['384', '0', '0'],
        ];
    }
}
