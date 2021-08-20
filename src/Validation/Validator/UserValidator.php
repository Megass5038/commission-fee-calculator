<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Validation\Validator;

use Kalashnik\CommissionTask\Entity\User;
use Kalashnik\CommissionTask\Validation\BaseValidator;
use Kalashnik\CommissionTask\Validation\Rule\InArray;
use Kalashnik\CommissionTask\Validation\Rule\Integer;

class UserValidator extends BaseValidator
{
    /**
     * Example of valid items format:
     * ["user_id" => "25", "type" => "business"]
     * ["user_id" => "26", "type" => "private"]
     * @return array
     */
    protected function rules(): array
    {
        return [
            'user_id' => [
                new Integer(true, false),
            ],
            'user_type' => [
                new InArray(User::availableTypes()),
            ],
        ];
    }
}
