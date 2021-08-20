<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Input\Mapper\CSV;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\User;
use Kalashnik\CommissionTask\Entity\Value\Money;
use Kalashnik\CommissionTask\Exception\Validation\InvalidItemAttributesCountException;
use Kalashnik\CommissionTask\Exception\Validation\ValidationException;
use Kalashnik\CommissionTask\Service\Input\Mapper\MapperInterface;
use Kalashnik\CommissionTask\Validation\BaseValidator;

class Mapper implements MapperInterface
{
    protected const COLUMNS_COUNT = 6;

    protected const COL_DATE_IDX = 0;
    protected const COL_USER_ID_IDX = 1;
    protected const COL_USER_TYPE_IDX = 2;
    protected const COL_TYPE_IDX = 3;
    protected const COL_AMOUNT_IDX = 4;
    protected const COL_CURRENCY_IDX = 5;
    protected ConfigContract $config;
    protected BaseValidator $userValidator;
    protected BaseValidator $operationValidator;

    public function __construct(ConfigContract $config, BaseValidator $operationValidator, BaseValidator $userValidator)
    {
        $this->config = $config;
        $this->operationValidator = $operationValidator;
        $this->userValidator = $userValidator;
    }

    /**
     * Transform array to Operation.
     * Expected format: [{date}, {user_id}, {user_type}, {type}, {amount}, {currency}]
     * @param array $item
     * @return Operation
     * @throws ValidationException
     */
    public function mapItemToOperation(array $item): Operation
    {
        $assocItem = $this->convertItemToAssocArray($item);
        $this->validateItem($assocItem);
        return $this->createOperation($assocItem);
    }

    /**
     * @param array $item
     * @return array
     * @throws InvalidItemAttributesCountException
     */
    protected function convertItemToAssocArray(array $item): array
    {
        $columnsCount = count($item);
        if ($columnsCount !== static::COLUMNS_COUNT) {
            throw new InvalidItemAttributesCountException(
                vsprintf('Item has %d instead of %d', [$columnsCount, static::COLUMNS_COUNT]), $item
            );
        }
        return [
            'date' => $item[self::COL_DATE_IDX],
            'user_id' => $item[self::COL_USER_ID_IDX],
            'user_type' => $item[self::COL_USER_TYPE_IDX],
            'type' => $item[self::COL_TYPE_IDX],
            'amount' => $item[self::COL_AMOUNT_IDX],
            'currency' => $item[self::COL_CURRENCY_IDX],
        ];
    }

    /**
     * @param array $item
     * @throws ValidationException
     */
    protected function validateItem(array $item)
    {
        $this->operationValidator->validate($item);
        $this->userValidator->validate($item);
    }

    protected function createOperation(array $item): Operation
    {
        $user = new User(
            intval($item['user_id']),
            $item['user_type']
        );
        $date = CarbonImmutable::parse($item['date']);
        $money = new Money($item['amount'], $item['currency']);
        return new Operation(
            $date,
            $user,
            $item['type'],
            $money
        );
    }
}
