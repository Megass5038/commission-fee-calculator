<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Validation\Rule;

use Kalashnik\CommissionTask\Validation\RuleInterface;

/**
 * Checks if a value is in specified array.
 */
class InArray implements RuleInterface
{
    /**
     * The rule will be passed if the value is in the array
     * @var array
     */
    private array $possibleValues;
    /**
     * Parameter for array_search function
     * @var bool
     */
    private bool $strict;

    public function __construct(array $possibleValues, bool $strict = true)
    {
        $this->possibleValues = $possibleValues;
        $this->strict = $strict;
    }

    public function passes(string $attribute, array $item): bool
    {
        return isset($item[$attribute])
                && array_search($item[$attribute], $this->possibleValues, $this->strict) !== false;
    }
}
