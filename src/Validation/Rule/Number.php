<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Validation\Rule;

use Kalashnik\CommissionTask\Validation\RuleInterface;

/**
 * Checks if a value is a valid number.
 */
class Number implements RuleInterface
{
    /**
     * The rule will be passed only if value will be positive number
     * @var bool
     */
    private bool $onlyPositive;
    /**
     * The rule will be passed only if value will be negative number
     * @var bool
     */
    private bool $onlyNegative;

    public function __construct(bool $onlyPositive = false, bool $onlyNegative = false)
    {
        $this->onlyPositive = $onlyPositive;
        $this->onlyNegative = $onlyNegative;
    }

    public function passes(string $attribute, array $item): bool
    {
        $isNumber = isset($item[$attribute]) && is_numeric($item[$attribute]);
        if (!$isNumber) {
            return false;
        }
        $floatVal = floatval($item[$attribute]);
        if ($this->onlyPositive) {
            return $floatVal > 0;
        } elseif ($this->onlyNegative) {
            return $floatVal < 0;
        }
        return true;
    }
}
