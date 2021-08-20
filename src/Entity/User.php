<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Entity;

class User
{
    public const TYPE_PRIVATE = 'private';
    public const TYPE_BUSINESS = 'business';

    private int $id;

    /**
     * Enum [self::TYPE_PRIVATE, self::TYPE_BUSINESS]
     * @var string
     */
    private string $type;

    public function __construct(int $id, string $type)
    {
        $this->id = $id;
        $this->setType($type);
    }

    public static function availableTypes(): array
    {
        return [
            static::TYPE_PRIVATE,
            static::TYPE_BUSINESS,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isBusiness(): bool
    {
        return $this->getType() === static::TYPE_BUSINESS;
    }

    public function isPrivate(): bool
    {
        return $this->getId() === static::TYPE_PRIVATE;
    }

    public function equalTo(User $user): bool
    {
        return $this->getId() === $user->getId();
    }
}
