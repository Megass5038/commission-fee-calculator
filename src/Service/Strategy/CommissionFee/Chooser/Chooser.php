<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Strategy\CommissionFee\Chooser;

use Carbon\CarbonPeriod;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use InvalidArgumentException;
use Kalashnik\CommissionTask\Entity\Operation;
use Kalashnik\CommissionTask\Entity\Value\Money;
use Kalashnik\CommissionTask\Exception\Strategy\CommissionFee\InvalidOperationTypeException;
use Kalashnik\CommissionTask\Repository\Operation\OperationRepositoryInterface;
use Kalashnik\CommissionTask\Service\Calculator\MoneyInterface as MoneyCalculatorInterface;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\SimplePercent;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\StrategyInterface;
use Kalashnik\CommissionTask\Service\Strategy\CommissionFee\Withdraw\WithSpentAmount;

/**
 * Choose strategy which should be used to calculate commission.
 * Any logic to change the commission calculation policy should be added to this class.
 * Or with significant changes prepare new Chooser with implementation corresponding interface.
 */
class Chooser implements ChooserInterface
{
    protected OperationRepositoryInterface $operationRepository;
    protected ConfigContract $config;
    protected MoneyCalculatorInterface $moneyCalculator;

    public function __construct(
        ConfigContract $config,
        OperationRepositoryInterface $operationRepository,
        MoneyCalculatorInterface $moneyCalculator
    ) {
        $this->operationRepository = $operationRepository;
        $this->config = $config;
        $this->moneyCalculator = $moneyCalculator;
    }

    /**
     * @param Operation $operation
     * @return StrategyInterface
     * @throws InvalidOperationTypeException
     */
    public function chooseCommissionStrategyForOperation(Operation $operation): StrategyInterface
    {
        if ($operation->isDeposit()) {
            return $this->chooseCommissionStrategyForDepositOperation($operation);
        } elseif ($operation->isWithdraw()) {
            return $this->chooseCommissionStrategyForWithdrawOperation($operation);
        }
        throw new InvalidOperationTypeException(sprintf('Invalid operation type [%s]', $operation->getType()), $operation);
    }

    protected function chooseCommissionStrategyForDepositOperation(Operation $operation): StrategyInterface
    {
        if ($operation->getUser()->isBusiness()) {
            return new SimplePercent(
                $this->moneyCalculator,
                $this->config->get('commission_fee.deposit.business_user.percent')
            );
        } else {
            return new SimplePercent(
                $this->moneyCalculator,
                $this->config->get('commission_fee.deposit.private_user.percent')
            );
        }
    }

    protected function chooseCommissionStrategyForWithdrawOperation(Operation $operation): StrategyInterface
    {
        if ($operation->getUser()->isBusiness()) {
            return new SimplePercent(
                $this->moneyCalculator,
                $this->config->get('commission_fee.withdraw.business_user.percent')
            );
        } else {
            return $this->chooseCommissionStrategyForPrivateUserWithdrawOperation($operation);
        }
    }

    protected function chooseCommissionStrategyForPrivateUserWithdrawOperation(Operation $operation): StrategyInterface
    {
        $maxFreeOperationsPerWeek = (int)$this->config->get('commission_fee.withdraw.private_user.max_free_operations_per_week');
        $privateUserCommissionPercent = (string)$this->config->get('commission_fee.withdraw.private_user.percent');

        $operationDate = $operation->getDate();
        $operationWeekPeriod = CarbonPeriod::create(
            $operationDate->startOfWeek(),
            $operationDate->copy()
        );

        // Free limit is reset every week (therefore we need all operations in the same week)
        $weeklyOperations = $this->operationRepository->getUserWithdrawOperationsForDatePeriod(
            $operation->getUser(), $operationWeekPeriod
        );
        $weeklyOperationsCount = count($weeklyOperations);

        if ($weeklyOperationsCount >= $maxFreeOperationsPerWeek) {
            // User exceeded maximum number of free operations
            return new SimplePercent(
                $this->moneyCalculator,
                $privateUserCommissionPercent
            );
        } else {
            if ($weeklyOperationsCount === 0) {
                // Means not a single operation in a week, so nothing has been spent yet
                $operationsAmount = new Money('0.00', $operation->getMoney()->getCurrency());
            } else {
                // Already spent amount
                $operationsAmount = $this->calculateOperationsAmount(...$weeklyOperations);
            }
            // Maximum free amount per week
            $limitAmount = new Money(
                (string)$this->config->get('commission_fee.withdraw.private_user.free_limit_per_week'),
                (string)$this->config->get('currency.base')
            );
            return new WithSpentAmount(
                $this->moneyCalculator,
                $operationsAmount,
                $limitAmount,
                $privateUserCommissionPercent
            );
        }
    }

    /**
     * Helper for calculating total amount for all operations passed to the method.
     * @param Operation ...$operations
     * @return Money
     * @throws InvalidArgumentException
     */
    private function calculateOperationsAmount(Operation ...$operations): Money
    {
        if (count($operations) === 0) {
            throw new InvalidArgumentException('calculateOperationsAmount method require at least one argument');
        }
        $operationsMoney = array_map(function (Operation $operation) {
            return $operation->getMoney();
        }, $operations);
        return $this->moneyCalculator->sum(...$operationsMoney);
    }
}
