<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Validation\Rule;

use Kalashnik\CommissionTask\Validation\RuleInterface;

/**
 * Checks if a value is a valid integer.
 */
class Integer implements RuleInterface
{
    /**
     * The rule will be passed only if value will be positive integer number
     * @var bool
     */
    private bool $onlyPositive;
    /**
     * The rule will be passed only if value will be negative integer number
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
        $floatIntVal = floatval(intval($item[$attribute]));

        // need to make sure that these are not float values
        if ($floatIntVal !== $floatVal) {
            return false;
        }
        if ($this->onlyPositive) {
            return $floatIntVal > 0;
        } elseif ($this->onlyNegative) {
            return $floatIntVal < 0;
        }
        return true;
    }
}
