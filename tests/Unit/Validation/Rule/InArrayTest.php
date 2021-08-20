<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Validation\Rule;

use PHPUnit\Framework\TestCase;
use Kalashnik\CommissionTask\Validation\Rule\InArray;

class InArrayTest extends TestCase
{
    private InArray $inArrayRule;
    private InArray $inArrayStrictFalseRule;

    public function setUp()
    {
        $this->inArrayRule = new InArray([
            'valid value 1',
            'valid value 2',
            'valid value 3',
            4,
            5,
        ], true);
        $this->inArrayStrictFalseRule = new InArray([
            4,
            '5',
        ], false);
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestPasses
     */
    public function testPasses($value)
    {
        $this->assertTrue(
            $this->inArrayRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestPassesFalse
     */
    public function testPassesFalse($value)
    {
        $this->assertFalse(
            $this->inArrayRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestPassesStrictFalse
     */
    public function testPassesStrictFalse($value)
    {
        $this->assertTrue(
            $this->inArrayStrictFalseRule->passes('value', ['value' => $value])
        );
    }

    public function dataProviderForTestPasses(): array
    {
        return [
            'valid value 1' => ['valid value 1'],
            'valid value 2' => ['valid value 2'],
            'valid value 3' => ['valid value 3'],
            'valid value 4' => [4],
            'valid value 5' => [5],
        ];
    }

    public function dataProviderForTestPassesFalse(): array
    {
        return [
            'invalid missed value 1' => [''],
            'invalid value 2' => ['4'],
            'invalid value 3' => ['5'],
        ];
    }

    public function dataProviderForTestPassesStrictFalse(): array
    {
        return [
            'valid string 1' => ['4'],
            'valid string 2' => [5],
        ];
    }
}
