<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Strategy\CommissionFee\Withdraw;

use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\Value\Money;
use Kalashnik\CommissionTask\Service\Calculator\MoneyInterface as MoneyCalculatorInterface;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\StrategyInterface;

class WithSpentAmount implements StrategyInterface
{
    private MoneyCalculatorInterface $moneyCalculator;
    private Money $spentAmount;
    private Money $limitAmount;
    private string $commissionPercent;

    /**
     * If user already exceeded $limitAmount then it takes a percentage of the operation amount.
     * If user not exceed $limitAmount then the part that is still unused will be free,
     * and for the exceeded one the commission will already be calculated.
     * For example: if $spentAmount = 500 EUR, $limitAmount = 1000 EUR and $operationAmount = 1000 EUR then
     * commission will be calculated only for 500 EUR because $freeAmount = ($limitAmount - $spentAmount) and
     * $operationAmount - $freeAmount = 500 EUR
     * @param MoneyCalculatorInterface $moneyCalculator
     * @param Money $spentAmount
     * @param Money $limitAmount
     * @param string $commissionPercent
     */
    public function __construct(
        MoneyCalculatorInterface $moneyCalculator,
        Money $spentAmount,
        Money $limitAmount,
        string $commissionPercent
    ) {
        $this->moneyCalculator = $moneyCalculator;
        $this->spentAmount = $spentAmount;
        $this->limitAmount = $limitAmount;
        $this->commissionPercent = $commissionPercent;
    }

    public function getSpentAmount(): Money
    {
        return $this->spentAmount;
    }

    public function getLimitAmount(): Money
    {
        return $this->limitAmount;
    }

    public function calculateCommissionFeeForOperation(Operation $operation): Money
    {
        $operationMoney = $operation->getMoney();
        $operationCurrency = $operationMoney->getCurrency();

        $availableFreeAmount = $this->moneyCalculator->sub($this->limitAmount, $this->spentAmount);

        if ($this->moneyCalculator->gte($availableFreeAmount, $operationMoney)) {
            // The operation amount is within the free limit therefore there is no commission for it
            return new Money('0.00', $operationMoney->getCurrency());
        }

        $availableFreeAmountMoreThanZero = $this->moneyCalculator->gtZero($availableFreeAmount);
        if ($availableFreeAmountMoreThanZero) {
            // Means that we still have a part of the free limit for partial repayment of the commission
            $operationMoneyWithRemainingLimit = $this->moneyCalculator->sub($operationMoney, $availableFreeAmount);
            $commissionMoney = $this->moneyCalculator->percentOfAmount($operationMoneyWithRemainingLimit, $this->commissionPercent);

            if ($commissionMoney->getCurrency() !== $operationCurrency) {
                // Output commission can be only in the operation currency (if not - cast it)
                return $this->moneyCalculator->convertMoneyFromBaseCurrency($commissionMoney, $operationCurrency);
            } else {
                return $commissionMoney;
            }
        } else {
            // Means that user already exceeded the allowed limit
            return $this->moneyCalculator->percentOfAmount($operationMoney, $this->commissionPercent);
        }
    }
}
