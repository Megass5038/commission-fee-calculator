<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Service\Formatter;

use PHPUnit\Framework\TestCase;
use Illuminate\Config\Repository as ConfigRepository;
use Kalashnik\CommissionTask\Entity\Value\Money;
use Kalashnik\CommissionTask\Service\Formatter\Commission;
use Kalashnik\CommissionTask\Service\Math\Math;

class CommissionTest extends TestCase
{
    private Commission $formatter;

    public function setUp()
    {
        $config = new ConfigRepository([
            'currency' => [
                'format' => [
                    'default' => [
                        'decimals' => 2,
                        'decimal_separator' => '.',
                        'thousands_separator' => '',
                    ],
                    'currencies' => [
                        'JPY' => [
                            'decimals' => 0,
                        ]
                    ]
                ]
            ]
        ]);
        $this->formatter = new Commission($config, new Math(6));
    }

    /**
     * @param Money $money
     * @param string $expectedAmount
     * @dataProvider dataProviderForTestFormatCommission
     */
    public function testFormatCommission(Money $money, string $expectedAmount)
    {
        $actualAmount = $this->formatter->formatCommission($money);
        $this->assertSame(
            $expectedAmount, $actualAmount
        );
    }

    public function dataProviderForTestFormatCommission(): array
    {
        return [
            [
                new Money('25.25111', 'USD'),
                '25.26'
            ],
            [
                new Money('25', 'USD'),
                '25.00'
            ],
            [
                new Money('25.0000', 'USD'),
                '25.00'
            ],
            [
                new Money('8612.1', 'JPY'),
                '8613'
            ],
        ];
    }
}
