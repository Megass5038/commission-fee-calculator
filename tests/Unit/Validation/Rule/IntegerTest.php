<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Validation\Rule;

use PHPUnit\Framework\TestCase;
use Kalashnik\CommissionTask\Validation\Rule\Integer as IntegerRule;

class IntegerTest extends TestCase
{
    private IntegerRule $allIntegersRule;
    private IntegerRule $onlyPositiveIntegerRule;
    private IntegerRule $onlyNegativeIntegerRule;

    public function setUp()
    {
        $this->allIntegersRule = new IntegerRule(false, false);
        $this->onlyPositiveIntegerRule = new IntegerRule(true, false);
        $this->onlyNegativeIntegerRule = new IntegerRule(false, true);
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestAllIntegersPasses
     */
    public function testAllIntegersPasses($value)
    {
        $this->assertTrue(
            $this->allIntegersRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestAllIntegersPassesFalse
     */
    public function testAllIntegersPassesFalse($value)
    {
        $this->assertFalse(
            $this->allIntegersRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestOnlyPositiveIntegerPasses
     */
    public function testOnlyPositiveIntegerPasses($value)
    {
        $this->assertTrue(
            $this->onlyPositiveIntegerRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestOnlyPositiveIntegerPassesFalse
     */
    public function testOnlyPositiveIntegerPassesFalse($value)
    {
        $this->assertFalse(
            $this->onlyPositiveIntegerRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestOnlyNegativeIntegerPasses
     */
    public function testOnlyNegativeIntegerPasses($value)
    {
        $this->assertTrue(
            $this->onlyNegativeIntegerRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestOnlyNegativeIntegerPassesFalse
     */
    public function testOnlyNegativeIntegerPassesFalse($value)
    {
        $this->assertFalse(
            $this->onlyNegativeIntegerRule->passes('value', ['value' => $value])
        );
    }

    public function dataProviderForTestAllIntegersPasses(): array
    {
        return [
            'valid value 1' => ['1'],
            'valid value 2' => [2],
            'valid value 3' => [250],
            'valid value 4' => ['0'],
            'valid value 5' => [-15],
        ];
    }

    public function dataProviderForTestAllIntegersPassesFalse(): array
    {
        return [
            'missed value 1' => [''],
            'invalid value 1' => [2.252],
            'invalid value 2' => ['2.55'],
            'invalid value 3' => ['invalid str'],
        ];
    }

    public function dataProviderForTestOnlyPositiveIntegerPasses(): array
    {
        return [
            'valid value 1' => ['1'],
            'valid value 2' => [2],
            'valid value 3' => [250],
        ];
    }

    public function dataProviderForTestOnlyPositiveIntegerPassesFalse(): array
    {
        return [
            'missed value 1' => [''],
            'invalid value 1' => [2.252],
            'invalid value 2' => ['2.55'],
            'invalid value 3' => ['invalid str'],
            'invalid value 4' => [0],
            'invalid value 5' => ['-5'],
            'invalid value 6' => ['-3'],
        ];
    }

    public function dataProviderForTestOnlyNegativeIntegerPasses(): array
    {
        return [
            'valid value 1' => ['-1'],
            'valid value 2' => [-2],
            'valid value 3' => [-250],
        ];
    }

    public function dataProviderForTestOnlyNegativeIntegerPassesFalse(): array
    {
        return [
            'missed value 1' => [''],
            'invalid value 1' => [-2.252],
            'invalid value 2' => ['-2.55'],
            'invalid value 3' => ['invalid str'],
            'invalid value 4' => [0],
            'invalid value 5' => ['5'],
            'invalid value 6' => ['3'],
        ];
    }
}
