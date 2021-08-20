<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Repository\Operation;

use Carbon\CarbonPeriod;
use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\User;

/**
 * Provides methods to select and save operations regardless of data storage.
 */
interface OperationRepositoryInterface
{
    public function save(Operation $operation): void;
    /**
     * @param User $user
     * @param CarbonPeriod $period
     * @return array<Operation>
     */
    public function getUserWithdrawOperationsForDatePeriod(User $user, CarbonPeriod $period): array;
}
