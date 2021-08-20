<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Validation\Rule;

use PHPUnit\Framework\TestCase;
use Kalashnik\CommissionTask\Validation\Rule\NotEmpty;

class NotEmptyTest extends TestCase
{
    private NotEmpty $notEmptyRule;

    public function setUp()
    {
        $this->notEmptyRule = new NotEmpty();
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestPasses
     */
    public function testPasses($value)
    {
        $this->assertTrue(
            $this->notEmptyRule->passes('value', ['value' => $value])
        );
    }

    /**
     * @param mixed int|string $value
     * @dataProvider dataProviderForTestPassesFalse
     */
    public function testPassesFalse($value)
    {
        $this->assertFalse(
            $this->notEmptyRule->passes('value', ['value' => $value])
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
        ];
    }
}
