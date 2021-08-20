<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Entity\ExchangeRate;

use Illuminate\Support\Arr;
use Kalashnik\CommissionTask\Entity\Value\Money;
use Kalashnik\CommissionTask\Exception\ExchangeRate\InvalidCurrencyException;

class ExchangeRate implements ExchangeRateInterface
{
    private string $baseCurrency;
    private array $rates;
    private ?int $timestamp;

    /**
     * @param array $rates Array with exchange rates in format {currency} => {rate}
     * @param string $baseCurrency
     * @param int|null $timestamp Time of receiving rates
     */
    public function __construct(array $rates, string $baseCurrency, ?int $timestamp)
    {
        $this->baseCurrency = $baseCurrency;
        $this->timestamp = $timestamp;
        $this->setRates($rates);
    }

    /**
     * @param string $currency
     * @return Money
     * @throws InvalidCurrencyException
     */
    public function getRate(string $currency): Money
    {
        if (!Arr::has($this->rates, $currency)) {
            throw new InvalidCurrencyException('Unknown currency ' . $currency, $currency);
        } else {
            return new Money($this->rates[$currency], $currency);
        }
    }

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    private function setRates(array $rates): void
    {
        $castedRates = array_map(function ($rate) {
            return (string) $rate;
        }, $rates);

        $this->rates = $castedRates;
    }
}
