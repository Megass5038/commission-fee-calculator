<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Validation\Rule;

use PHPUnit\Framework\TestCase;
use Kalashnik\CommissionTask\Validation\Rule\Number as NumberRule;

class NumberTest extends TestCase
{
    private NumberRule $allNumbersRule;
    private NumberRule $onlyPositiveNumberRule;
    private NumberRule $onlyNegativeNumberRule;

    public function setUp()
    {
        $this->allNumbersRule = new NumberRule(false, false);
        $this->onlyPositiveNumberRule = new NumberRule(true, false);
        $this->onlyNegativeNumberRule = new NumberRule(false, true);
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestAllNumbersPasses
     */
    public function testAllNumbersPasses($value)
    {
        $this->assertTrue(
            $this->allNumbersRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestAllNumbersPassesFalse
     */
    public function testAllNumbersPassesFalse($value)
    {
        $this->assertFalse(
            $this->allNumbersRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestOnlyPositiveNumberPasses
     */
    public function testOnlyPositiveNumberPasses($value)
    {
        $this->assertTrue(
            $this->onlyPositiveNumberRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestOnlyPositiveNumberPassesFalse
     */
    public function testOnlyPositiveNumberPassesFalse($value)
    {
        $this->assertFalse(
            $this->onlyPositiveNumberRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestOnlyNegativeNumberPasses
     */
    public function testOnlyNegativeNumberPasses($value)
    {
        $this->assertTrue(
            $this->onlyNegativeNumberRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestOnlyNegativeNumberPassesFalse
     */
    public function testOnlyNegativeNumberPassesFalse($value)
    {
        $this->assertFalse(
            $this->onlyNegativeNumberRule->passes('value', ['value' => $value])
        );
    }

    public function dataProviderForTestAllNumbersPasses(): array
    {
        return [
            'valid value 1' => ['1'],
            'valid value 2' => [2],
            'valid value 3' => [250.25],
            'valid value 4' => ['0.25'],
            'valid value 5' => [-15.001],
        ];
    }

    public function dataProviderForTestAllNumbersPassesFalse(): array
    {
        return [
            'missed value 1' => [''],
            'invalid value 1' => ['0,55'],
            'invalid value 3' => ['invalid str'],
        ];
    }

    public function dataProviderForTestOnlyPositiveNumberPasses(): array
    {
        return [
            'valid value 1' => ['1.1'],
            'valid value 2' => [2.15],
            'valid value 3' => [250],
            'valid value 4' => ['256'],
        ];
    }

    public function dataProviderForTestOnlyPositiveNumberPassesFalse(): array
    {
        return [
            'missed value 1' => [''],
            'invalid value 1' => [-2.252],
            'invalid value 2' => ['-2.55'],
            'invalid value 3' => ['invalid str'],
            'invalid value 4' => [0],
            'invalid value 5' => [-5],
            'invalid value 6' => ['-3'],
        ];
    }

    public function dataProviderForTestOnlyNegativeNumberPasses(): array
    {
        return [
            'valid value 1' => ['-1.1'],
            'valid value 2' => [-2.2],
            'valid value 3' => [-250],
            'valid value 4' => ['-250'],
        ];
    }

    public function dataProviderForTestOnlyNegativeNumberPassesFalse(): array
    {
        return [
            'missed value 1' => [''],
            'invalid value 1' => [2.252],
            'invalid value 2' => ['2.55'],
            'invalid value 3' => ['invalid str'],
            'invalid value 4' => [0],
            'invalid value 5' => [5],
            'invalid value 6' => ['3'],
        ];
    }
}
