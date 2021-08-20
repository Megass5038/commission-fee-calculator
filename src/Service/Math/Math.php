<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Math;

use DivisionByZeroError;
use Kalashnik\CommissionTask\Exception\Math\NotWellFormedValueException;

/**
 * Performs mathematical operations with the specified scale via bcmath extension.
 * Note: when providing invalid numbers as a string then NotWellFormedValueException will be thrown
 */
class Math implements MathInterface
{
    private int $scale;

    public function __construct(int $scale)
    {
        $this->setScale($scale);
    }

    public function getScale(): int
    {
        return $this->scale;
    }

    public function setScale(int $scale): void
    {
        $this->scale = $scale;
    }

    /**
     * Perform ">" operation.
     * @param string $leftOperand
     * @param string $rightOperand
     * @return bool
     * @throws NotWellFormedValueException
     */
    public function gt(string $leftOperand, string $rightOperand): bool
    {
        $this->validateOperands($leftOperand, $rightOperand);
        return bccomp($leftOperand, $rightOperand, $this->scale) === static::GT;
    }

    /**
     * Perform ">=" operation.
     * @param string $leftOperand
     * @param string $rightOperand
     * @return bool
     * @throws NotWellFormedValueException
     */
    public function gte(string $leftOperand, string $rightOperand): bool
    {
        $this->validateOperands($leftOperand, $rightOperand);
        $compResult = bccomp($leftOperand, $rightOperand, $this->scale);
        return $compResult === static::GT || $compResult === static::EQ;
    }

    /**
     * Perform "<" operation.
     * @param string $leftOperand
     * @param string $rightOperand
     * @return bool
     * @throws NotWellFormedValueException
     */
    public function lt(string $leftOperand, string $rightOperand): bool
    {
        $this->validateOperands($leftOperand, $rightOperand);
        return bccomp($leftOperand, $rightOperand, $this->scale) === static::LT;
    }

    /**
     * Perform "<=" operation.
     * @param string $leftOperand
     * @param string $rightOperand
     * @return bool
     * @throws NotWellFormedValueException
     */
    public function lte(string $leftOperand, string $rightOperand): bool
    {
        $this->validateOperands($leftOperand, $rightOperand);
        $compResult = bccomp($leftOperand, $rightOperand, $this->scale);
        return $compResult === static::LT || $compResult === static::EQ;
    }

    /**
     * Perform "==" operation.
     * @param string $leftOperand
     * @param string $rightOperand
     * @return bool
     * @throws NotWellFormedValueException
     */
    public function eq(string $leftOperand, string $rightOperand): bool
    {
        $this->validateOperands($leftOperand, $rightOperand);
        return bccomp($leftOperand, $rightOperand, $this->scale) === static::EQ;
    }

    /**
     * Perform "+" operation.
     * @param string $leftOperand
     * @param string $rightOperand
     * @return string
     * @throws NotWellFormedValueException
     */
    public function add(string $leftOperand, string $rightOperand): string
    {
        $this->validateOperands($leftOperand, $rightOperand);
        return bcadd($leftOperand, $rightOperand, $this->scale);
    }

    /**
     * Perform "-" operation.
     * @param string $leftOperand
     * @param string $rightOperand
     * @return string
     * @throws NotWellFormedValueException
     */
    public function sub(string $leftOperand, string $rightOperand): string
    {
        $this->validateOperands($leftOperand, $rightOperand);
        return bcsub($leftOperand, $rightOperand, $this->scale);
    }

    /**
     * Perform "/" operation.
     * @param string $dividend
     * @param string $divisor
     * @return string
     * @throws NotWellFormedValueException
     */
    public function div(string $dividend, string $divisor): string
    {
        $this->validateOperands($dividend, $divisor);
        if (intval($divisor) === 0) {
            throw new DivisionByZeroError();
        }
        return bcdiv($dividend, $divisor, $this->scale);
    }

    /**
     * Perform "*" operation.
     * @param string $leftOperand
     * @param string $rightOperand
     * @return string
     * @throws NotWellFormedValueException
     */
    public function mul(string $leftOperand, string $rightOperand): string
    {
        $this->validateOperands($leftOperand, $rightOperand);
        return bcmul($leftOperand, $rightOperand, $this->scale);
    }

    /**
     * Helper for ceil values with decimal places.
     * Example: ("25.2567", 2) -> "25.26", ("25.2567", 0) -> "26"
     * @param string $value
     * @param int $precision
     * @return string
     * @throws NotWellFormedValueException
     */
    public function ceil(string $value, int $precision = 0): string
    {
        $this->validateOperands($value);
        $result = ceil($value * pow(10, $precision)) / pow(10, $precision);
        return (string) $result;
    }

    /**
     * Calculates percent of number.
     * @param string $number
     * @param string $percent
     * @return string
     * @throws NotWellFormedValueException
     */
    public function percentOfNumber(string $number, string $percent): string
    {
        $this->validateOperands($number, $percent);
        return $this->mul(
            $this->div($percent, '100'),
            $number
        );
    }

    /**
     * Helper for validating input numbers.
     * @param string ...$operands
     * @throws NotWellFormedValueException
     */
    private function validateOperands(string ...$operands): void
    {
        foreach ($operands as $operand) {
            if (!is_numeric($operand)) {
                throw new NotWellFormedValueException('The value "' . $operand . '" is not valid number');
            }
        }
    }
}
