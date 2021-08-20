<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Validation\Rule;

use PHPUnit\Framework\TestCase;
use Kalashnik\CommissionTask\Validation\Rule\Date;

class DateTest extends TestCase
{
    private Date $dateRule;

    public function setUp()
    {
        $this->dateRule = new Date('Y-m-d');
    }

    /**
     * @param string $value
     * @dataProvider dataProviderForTestPasses
     */
    public function testPasses(string $value)
    {
        $this->assertTrue(
            $this->dateRule->passes('date', ['date' => $value])
        );
    }

    /**
     * @param string $value
     * @dataProvider dataProviderForTestPassesFalse
     */
    public function testPassesFalse(string $value)
    {
        $this->assertFalse(
            $this->dateRule->passes('date', ['date' => $value])
        );
    }

    public function dataProviderForTestPasses(): array
    {
        return [
            'valid date 1' => ['2021-05-06'],
            'valid date 2' => ['2015-05-06'],
            'valid date 3' => ['2015-04-07'],
        ];
    }

    public function dataProviderForTestPassesFalse(): array
    {
        return [
            'invalid missed date 1' => [''],
            'invalid date format 1' => ['2015/05/06'],
            'invalid date format 2' => ['2020-13-10'],
        ];
    }
}
