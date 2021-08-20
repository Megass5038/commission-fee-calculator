<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Factory\Repository;

use Kalashnik\CommissionTask\Repository\Operation\FromArray;
use Kalashnik\CommissionTask\Repository\Operation\OperationRepositoryInterface;

class OperationRepositoryFactory
{
    /**
     * Provides OperationRepository. Currently only FromArray supported.
     * Note: each instance of FromArray has its own state.
     * @return OperationRepositoryInterface
     */
    public static function getRepository(): OperationRepositoryInterface
    {
        return new FromArray();
    }
}
