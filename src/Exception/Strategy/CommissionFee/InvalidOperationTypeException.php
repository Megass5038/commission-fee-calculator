<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Exception\Strategy\CommissionFee;

use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Exception\CommissionFeeException;
use Throwable;

class InvalidOperationTypeException extends CommissionFeeException
{
    private Operation $operation;

    public function __construct(
        string $message,
        Operation $operation,
        int $code = 0,
        Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->operation = $operation;
    }

    public function getOperation()
    {
        return $this->operation;
    }
}
