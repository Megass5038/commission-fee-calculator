<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Factory\ExchangeRate;

use Kalashnik\CommissionTask\Factory\Config\ConfigFactory;
use Kalashnik\CommissionTask\Service\ExchangeRate\Loader;
use Kalashnik\CommissionTask\Service\ExchangeRate\LoaderInterface;
use Kalashnik\CommissionTask\Service\ExchangeRate\StubLoader;

class LoaderFactory
{
    /**
     * Provides Loader of exchange rates depending on the specified configs.
     * @return LoaderInterface
     */
    public static function getLoader(): LoaderInterface
    {
        $config = ConfigFactory::getConfig();
        if ($config->get('exchange_rate.provider') === 'exchangeratesapi') {
            return new Loader(
                $config
            );
        } else {
            return new StubLoader(
                $config
            );
        }
    }
}
