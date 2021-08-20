<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Input\Mapper;

use Kalashnik\CommissionTask\Entity\Operation;

/**
 * Provides method for transform from raw array to Operation entity.
 */
interface MapperInterface
{
    public function mapItemToOperation(array $item): Operation;
}
