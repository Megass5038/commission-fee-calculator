<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Validation\Rule;

use Kalashnik\CommissionTask\Validation\RuleInterface;

/**
 * Checks if a value is not empty string.
 */
class NotEmpty implements RuleInterface
{
    public function passes(string $attribute, array $item): bool
    {
        return isset($item[$attribute]) && $item[$attribute] !== '';
    }
}
