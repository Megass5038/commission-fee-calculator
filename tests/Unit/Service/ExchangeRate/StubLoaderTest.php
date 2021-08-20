<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Tests\Unit\Service\ExchangeRate;

use PHPUnit\Framework\TestCase;
use Illuminate\Config\Repository as ConfigRepository;
use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRate;
use Kalashnik\CommissionTask\Entity\Value\Money;
use Kalashnik\CommissionTask\Service\ExchangeRate\StubLoader;

class StubLoaderTest extends TestCase
{
    private ConfigRepository $config;
    private StubLoader $stubLoader;

    public function setUp()
    {
        $this->config = new ConfigRepository([
            'currency' => [
                'base' => 'EUR'
            ],
            'exchange_rate' => [
                'providers' => [
                    'stub' => [
                        'rates' => [
                            'USD' => '1.1497',
                            'JPY' => '129.53',
                        ]
                    ]
                ]
            ]
        ]);
        $this->stubLoader = new StubLoader($this->config);
    }

    /**
     * @param Money $expectedMoney
     * @dataProvider dataProviderForTestLoadAndGetExchangeRate
     */
    public function testLoadAndGetExchangeRate(Money $expectedMoney)
    {
        $exchangeRate = $this->stubLoader->loadAndGetExchangeRate();
        $this->assertInstanceOf(ExchangeRate::class, $exchangeRate);

        $actualMoney = $exchangeRate->getRate($expectedMoney->getCurrency());

        $this->assertTrue(
            $actualMoney->equalTo($expectedMoney)
        );
    }

    public function testBaseCurrency()
    {
        $exchangeRate = $this->stubLoader->loadAndGetExchangeRate();
        $this->assertInstanceOf(ExchangeRate::class, $exchangeRate);

        $this->assertTrue(
            $exchangeRate->getBaseCurrency() === $this->config->get('currency.base')
        );
    }

    public function dataProviderForTestLoadAndGetExchangeRate()
    {
        return [
            'USD' => [
                new Money('1.1497', 'USD')
            ],
            'JPY' => [
                new Money('129.53', 'JPY')
            ],
        ];
    }
}
