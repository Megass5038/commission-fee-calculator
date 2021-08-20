<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Calculator;

use Kalashnik\CommissionTask\Entity\Value\Money as MoneyEntity;

/**
 * Provides methods for MoneyEntity calculation.
 */
interface MoneyInterface
{
    public function sum(MoneyEntity ...$moneyEntities): MoneyEntity;
    public function add(MoneyEntity $leftMoney, MoneyEntity $rightMoney): MoneyEntity;
    public function sub(MoneyEntity $leftMoney, MoneyEntity $rightMoney): MoneyEntity;
    public function gt(MoneyEntity $leftMoney, MoneyEntity $rightMoney): bool;
    public function gte(MoneyEntity $leftMoney, MoneyEntity $rightMoney): bool;

    /**
     * Helper to quickly detect if money.amount <= 0
     * @param MoneyEntity $money
     * @return bool
     */
    public function gtZero(MoneyEntity $money): bool;
    public function percentOfAmount(MoneyEntity $money, string $percent): MoneyEntity;
    public function convertMoneyToBaseCurrency(MoneyEntity $money): MoneyEntity;
    public function convertMoneyFromBaseCurrency(MoneyEntity $money, string $currency): MoneyEntity;
}
