<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Entity\Value;

class Money
{
    private string $amount;
    private string $currency;

    public function __construct(string $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Compares with provided Money entity.
     * Note: amount compared as string so 25 !== 25.00
     * @param Money $money
     * @return bool
     */
    public function equalTo(Money $money): bool
    {
        return $this->getAmount() === $money->getAmount()
                && $this->getCurrency() === $money->getCurrency();
    }
}
