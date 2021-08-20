<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Repository\Operation;

use Carbon\CarbonPeriod;
use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\User;

/**
 * Repository provides access to operations directly from its state.
 *
 * Shape of repository state (associative array):
  [
    "{operationDate1}-{user1}" => [Operation, ..., Operation],
     ...
    "{operationDateN}-{user1}" => [Operation, ..., Operation],
  ]
 * aka "Operation Date" - "UserID" index
 *
 * For live solutions it is recommended to replace with an implementation using real DB (like MySQL/Mongo...).
 */
class FromArray implements OperationRepositoryInterface
{
    protected const DATE_INDEX_FORMAT = 'Y-m-d';
    protected array $data = [];

    public function save(Operation $operation): void
    {
        $dateUserIdx = $this->getDateUserIndexKey(
            $operation->getDate()->format(static::DATE_INDEX_FORMAT),
            (string) $operation->getUser()->getId()
        );

        if (!isset($this->data[$dateUserIdx])) {
            $this->data[$dateUserIdx] = [];
        }

        $this->data[$dateUserIdx][] = $operation;
    }

    /**
     * Selects all user withdraw operations for specified date period.
     * @param User $user
     * @param CarbonPeriod $period
     * @return array<Operation>
     */
    public function getUserWithdrawOperationsForDatePeriod(User $user, CarbonPeriod $period): array
    {
        $operations = [];

        foreach ($period as $date) {
            $dateUserIdx = $this->getDateUserIndexKey(
                $date->format(static::DATE_INDEX_FORMAT),
                (string) $user->getId()
            );
            if (isset($this->data[$dateUserIdx])) {
                $withdrawOperations = array_filter($this->data[$dateUserIdx], function (Operation $operation) {
                    return $operation->isWithdraw();
                });
                $operations = array_merge($operations, $withdrawOperations);
            }
        }

        return $operations;
    }

    /**
     * Helper to generate the index key. Example: ("2021-08-01", "25") -> "2021-08-01-25"
     * @param string $date
     * @param string $userId
     * @return string
     */
    protected function getDateUserIndexKey(string $date, string $userId): string
    {
        return $date . '-' . $userId;
    }
}
