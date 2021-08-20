<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Validation;

use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Support\Arr;
use Kalashnik\CommissionTask\Exception\Validation\ValidationException;

/**
 * The class is for validation associative array.
 * Inheritors define validation rules for each field.
 * In case of failure of one of the rules, the ValidationException thrown
 */
abstract class BaseValidator
{
    protected ConfigContract $config;

    public function __construct(ConfigContract $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $item
     * @throws ValidationException
     */
    public function validate(array $item): void
    {
        $rulesByAttribute = $this->rules();
        foreach ($rulesByAttribute as $attribute => $rules) {
            /** @var array $rules */
            foreach ($rules as $rule) {
                /** @var RuleInterface $rule */
                if (!$rule->passes($attribute, $item)) {
                    $value = Arr::get($item, $attribute);
                    $errorMessage = vsprintf('(%s): check failed for attribute "%s" with value "%s"', [
                        get_class($rule),
                        $attribute,
                        $value,
                    ]);
                    throw new ValidationException($errorMessage, $item, 0, null);
                }
            }
        }
    }

    /**
     * @return array[string][array<RuleInterface>] expected format ["field1" => new Rule(), "field2" => new Rule()]
     */
    abstract protected function rules(): array;
}
