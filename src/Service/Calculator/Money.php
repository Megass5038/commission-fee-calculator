<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Calculator;

use InvalidArgumentException;
use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRateInterface;
use Kalashnik\CommissionTask\Entity\Value\Money as MoneyEntity;
use Kalashnik\CommissionTask\Exception\ExchangeRate\InvalidCurrencyException;
use Kalashnik\CommissionTask\Service\Math\MathInterface;

/**
 * Implements methods for MoneyEntity calculation.
 * Note: all operations with MoneyEntity performed only in same currency, so usd only with usd, eur only with eur, etc...
 * If Money entities provided in different currencies then it will cast to base currency and result will be in
 * base currency.
 * For example:
 * If $baseCurrency = EUR and you need to calculate 5 USD + 5 UAH then it will convert 5 USD to EUR and 5 UAH to EUR,
 * and final result will be in EUR.
 * If after that you need to get the results in another currency,
 * then you can use the method for converting from the base currency (convertMoneyFromBaseCurrency).
 */
class Money implements MoneyInterface
{
    private MathInterface $math;
    private ExchangeRateInterface $exchangeRate;

    public function __construct(MathInterface $math, ExchangeRateInterface $exchangeRate)
    {
        $this->math = $math;
        $this->exchangeRate = $exchangeRate;
    }

    /**
     * @param MoneyEntity ...$moneyEntities
     * @return MoneyEntity
     * @throws InvalidArgumentException
     */
    public function sum(MoneyEntity ...$moneyEntities): MoneyEntity
    {
        if (count($moneyEntities) === 0) {
            throw new InvalidArgumentException('sum method require at least one argument');
        }
        return array_reduce($moneyEntities, function (?MoneyEntity $money1, MoneyEntity $money2) {
            if ($money1 === null) {
                return $this->copy($money2);
            }
            return $this->add($money1, $money2);
        });
    }

    public function add(MoneyEntity $leftMoney, MoneyEntity $rightMoney): MoneyEntity
    {
        list($convertedLeftMoney, $convertedRightMoney) = $this->convertToBaseIfNotSameCurrency($leftMoney, $rightMoney);
        $resultAmount = $this->math->add(
            $convertedLeftMoney->getAmount(), $convertedRightMoney->getAmount()
        );
        return new MoneyEntity(
            $resultAmount,
            $convertedLeftMoney->getCurrency()
        );
    }

    public function sub(MoneyEntity $leftMoney, MoneyEntity $rightMoney): MoneyEntity
    {
        list($convertedLeftMoney, $convertedRightMoney) = $this->convertToBaseIfNotSameCurrency($leftMoney, $rightMoney);
        $resultAmount = $this->math->sub(
            $convertedLeftMoney->getAmount(), $convertedRightMoney->getAmount()
        );
        return new MoneyEntity(
            $resultAmount,
            $convertedLeftMoney->getCurrency()
        );
    }

    public function gt(MoneyEntity $leftMoney, MoneyEntity $rightMoney): bool
    {
        list($convertedLeftMoney, $convertedRightMoney) = $this->convertToBaseIfNotSameCurrency($leftMoney, $rightMoney);
        return $this->math->gt($convertedLeftMoney->getAmount(), $convertedRightMoney->getAmount());
    }

    public function gte(MoneyEntity $leftMoney, MoneyEntity $rightMoney): bool
    {
        list($convertedLeftMoney, $convertedRightMoney) = $this->convertToBaseIfNotSameCurrency($leftMoney, $rightMoney);
        return $this->math->gte($convertedLeftMoney->getAmount(), $convertedRightMoney->getAmount());
    }

    public function gtZero(MoneyEntity $money): bool
    {
        return $this->gt($money, new MoneyEntity('0.00', $money->getCurrency()));
    }

    public function percentOfAmount(MoneyEntity $money, string $percent): MoneyEntity
    {
        $percentOfAmount = $this->math->percentOfNumber($money->getAmount(), $percent);
        return new MoneyEntity(
            $percentOfAmount,
            $money->getCurrency()
        );
    }

    public function convertMoneyToBaseCurrency(MoneyEntity $money): MoneyEntity
    {
        if ($money->getCurrency() === $this->exchangeRate->getBaseCurrency()) {
            return $this->copy($money);
        }
        $fromCurrencyRateMoney = $this->exchangeRate->getRate($money->getCurrency());
        $convertedAmount = $this->math->div($money->getAmount(), $fromCurrencyRateMoney->getAmount());
        return new MoneyEntity(
            $convertedAmount,
            $this->exchangeRate->getBaseCurrency()
        );
    }

    /**
     * @param MoneyEntity $money
     * @param string $currency
     * @return MoneyEntity
     * @throws InvalidCurrencyException
     */
    public function convertMoneyFromBaseCurrency(MoneyEntity $money, string $currency): MoneyEntity
    {
        if ($money->getCurrency() !== $this->exchangeRate->getBaseCurrency()) {
            $errorMsg = sprintf(
                'convertMoneyFromBaseCurrency can be called only with base currency (%s) but was called with %s',
                $this->exchangeRate->getBaseCurrency(),
                $currency
            );
            throw new InvalidCurrencyException($errorMsg, $money->getCurrency());
        }
        $toCurrencyRateMoney = $this->exchangeRate->getRate($currency);
        $convertedAmount = $this->math->mul($money->getAmount(), $toCurrencyRateMoney->getAmount());
        return new MoneyEntity(
            $convertedAmount,
            $currency
        );
    }

    protected function copy(MoneyEntity $money): MoneyEntity
    {
        return new MoneyEntity(
            $money->getAmount(),
            $money->getCurrency()
        );
    }

    /**
     * Helper so that the operated money are in the same currency.
     * @param MoneyEntity $leftMoney
     * @param MoneyEntity $rightMoney
     * @return array<MoneyEntity>
     */
    protected function convertToBaseIfNotSameCurrency(MoneyEntity $leftMoney, MoneyEntity $rightMoney): array
    {
        if ($leftMoney->getCurrency() === $rightMoney->getCurrency()) {
            $convertedLeftMoney = $this->copy($leftMoney);
            $convertedRightMoney = $this->copy($rightMoney);
        } else {
            $convertedLeftMoney = $this->convertMoneyToBaseCurrency($leftMoney);
            $convertedRightMoney = $this->convertMoneyToBaseCurrency($rightMoney);
        }
        return [
            $convertedLeftMoney,
            $convertedRightMoney,
        ];
    }
}
