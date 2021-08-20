<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Validation\Rule;

use DateTimeImmutable;
use Kalashnik\CommissionTask\Validation\RuleInterface;

/**
 * Checks if a value is a valid date according to specified dateFormat via DateTime.
 */
class Date implements RuleInterface
{
    private string $dateFormat;

    public function __construct(string $dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    public function passes(string $attribute, array $item): bool
    {
        if (!isset($item[$attribute])) {
            return false;
        }
        $value = $item[$attribute];
        $parsedDate = DateTimeImmutable::createFromFormat($this->dateFormat, $value);

        return $parsedDate && $parsedDate->format($this->dateFormat) === $value;
    }
}
