<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Validation\Validator;

use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Validation\BaseValidator;
use Kalashnik\CommissionTask\Validation\Rule\Date;
use Kalashnik\CommissionTask\Validation\Rule\InArray;
use Kalashnik\CommissionTask\Validation\Rule\NotEmpty;
use Kalashnik\CommissionTask\Validation\Rule\Number;

class OperationValidator extends BaseValidator
{
    protected const EXPECTED_DATE_FORMAT = 'Y-m-d';

    /**
     * Example of valid items format:
     * ["date" => "2021-08-20", "type" => "withdraw", "amount" => "25.25", "currency" => "EUR"]
     * ["date" => "2021-08-20", "type" => "deposit", "amount" => "25.25", "currency" => "EUR"]
     * @return array
     */
    protected function rules(): array
    {
        return [
            'date' => [
                new Date(static::EXPECTED_DATE_FORMAT),
            ],
            'type' => [
                new InArray(Operation::availableTypes()),
            ],
            'amount' => [
                new Number(true, false),
            ],
            'currency' => [
                new NotEmpty(),
            ],
        ];
    }
}
