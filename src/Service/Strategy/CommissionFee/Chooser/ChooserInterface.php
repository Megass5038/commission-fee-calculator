<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Strategy\CommissionFee\Chooser;

use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\StrategyInterface;

/**
 * Provides method for choose StrategyImplementation which will be used when calculating the commission.
 */
interface ChooserInterface
{
    public function chooseCommissionStrategyForOperation(Operation $operation): StrategyInterface;
}
