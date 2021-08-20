<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Factory\Input;

use Kalashnik\CommissionTask\Factory\Config\ConfigFactory;
use Kalashnik\CommissionTask\Service\Input\Mapper\CSV\Mapper;
use Kalashnik\CommissionTask\Service\Input\Mapper\MapperInterface;
use Kalashnik\CommissionTask\Validation\Validator\OperationValidator;
use Kalashnik\CommissionTask\Validation\Validator\UserValidator;

class MapperFactory
{
    public static function getMapper(): MapperInterface
    {
        $config = ConfigFactory::getConfig();
        return new Mapper(
            $config,
            new OperationValidator($config),
            new UserValidator($config)
        );
    }
}
