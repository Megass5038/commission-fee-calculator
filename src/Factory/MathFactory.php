<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Factory;

use Kalashnik\CommissionTask\Service\Math\Math;
use Kalashnik\CommissionTask\Service\Math\MathInterface;

class MathFactory
{
    public static function getMath(int $scale): MathInterface
    {
        return new Math($scale);
    }
}
