<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Factory\Formatter;

use Kalashnik\CommissionTask\Factory\Config\ConfigFactory;
use Kalashnik\CommissionTask\Factory\MathFactory;
use Kalashnik\CommissionTask\Service\Formatter\Commission;
use Kalashnik\CommissionTask\Service\Formatter\FormatterInterface;

class CommissionFormatterFactory
{
    public static function getFormatter(): FormatterInterface
    {
        $config = ConfigFactory::getConfig();
        return new Commission(
            $config,
            MathFactory::getMath($config->get('math.scale'))
        );
    }
}
