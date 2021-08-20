<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Exception\Validation;

use Kalashnik\CommissionTask\Exception\CommissionFeeException;
use Throwable;

/**
 * For any validation failed
 */
class ValidationException extends CommissionFeeException
{
    public function __construct(string $message, array $item, int $code = 0, Throwable $previous = null)
    {
        $wrappedMessage = $this->wrapErrorMessage($message, $item);
        parent::__construct($wrappedMessage, $code, $previous);
    }

    protected function wrapErrorMessage(string $message, array $item): string
    {
        $itemSerialized = json_encode($item);
        return sprintf('**ValidationError** %s (raw item: %s)', $message, $itemSerialized);
    }
}
