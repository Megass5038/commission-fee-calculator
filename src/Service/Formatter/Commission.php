<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\Formatter;

use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Support\Arr;
use Kalashnik\CommissionTask\Entity\Value\Money;
use Kalashnik\CommissionTask\Service\Math\MathInterface;

class Commission implements FormatterInterface
{
    private ConfigContract $config;
    private MathInterface $math;

    public function __construct(ConfigContract $config, MathInterface $math)
    {
        $this->config = $config;
        $this->math = $math;
    }

    public function formatCommission(Money $money): string
    {
        $currency = $money->getCurrency();
        $defaultFormat = $this->config->get('currency.format.default');
        $format = array_merge(
            $defaultFormat,
            Arr::get($this->config->get('currency.format.currencies'), $currency, [])
        ); // if no configs for $currency then default configs will used
        $ceilAmount = $this->math->ceil($money->getAmount(), $format['decimals']);
        $result = number_format(
            (float)$ceilAmount,
            $format['decimals'],
            $format['decimal_separator'],
            $format['thousands_separator']
        );
        return (string) $result;
    }
}
