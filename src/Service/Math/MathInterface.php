<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Math;

/**
 * Provides methods to performing mathematical operations with the specified scale.
 */
interface MathInterface
{
    public const GT = 1;
    public const LT = -1;
    public const EQ = 0;

    public function getScale(): int;
    public function setScale(int $scale): void;

    public function gt(string $leftOperand, string $rightOperand): bool;
    public function gte(string $leftOperand, string $rightOperand): bool;
    public function lt(string $leftOperand, string $rightOperand): bool;
    public function lte(string $leftOperand, string $rightOperand): bool;
    public function eq(string $leftOperand, string $rightOperand): bool;
    public function add(string $leftOperand, string $rightOperand): string;
    public function div(string $dividend, string $divisor): string;
    public function mul(string $leftOperand, string $rightOperand): string;
    public function ceil(string $value, int $precision = 0): string;
    public function percentOfNumber(string $number, string $percent): string;
}
