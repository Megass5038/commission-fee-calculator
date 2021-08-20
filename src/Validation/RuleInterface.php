<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Validation;

/**
 * Provides method to check is attribute of item valid.
 */
interface RuleInterface
{
    public function passes(string $attribute, array $item): bool;
}
