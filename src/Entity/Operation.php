<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Entity;

use Carbon\CarbonImmutable;
use Kalashnik\CommissionTask\Entity\Value\Money;

class Operation
{
    public const TYPE_DEPOSIT = 'deposit';
    public const TYPE_WITHDRAW = 'withdraw';

    private CarbonImmutable $date;
    private User $user;

    /**
     * Enum [self::TYPE_DEPOSIT, self::TYPE_WITHDRAW]
     * @var string
     */
    private string $type;
    private Money $money;

    public function __construct(CarbonImmutable $date, User $user, string $type, Money $money)
    {
        $this->date = $date;
        $this->user = $user;
        $this->type = $type;
        $this->money = $money;
    }

    public static function availableTypes(): array
    {
        return [
          static::TYPE_DEPOSIT,
          static::TYPE_WITHDRAW,
        ];
    }

    public function getDate(): CarbonImmutable
    {
        return $this->date;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function isDeposit(): bool
    {
        return $this->getType() === static::TYPE_DEPOSIT;
    }

    public function isWithdraw(): bool
    {
        return $this->getType() === static::TYPE_WITHDRAW;
    }

    /**
     * Compares all properties to the corresponding properties from provided Operation entity
     * @param Operation $operation
     * @return bool
     */
    public function equalTo(Operation $operation): bool
    {
        return $operation->getUser()->equalTo($this->getUser())
            && $operation->getMoney()->equalTo($this->getMoney())
            && $operation->getType() === $this->getType()
            && $operation->getDate()->equalTo($this->getDate());
    }
}
