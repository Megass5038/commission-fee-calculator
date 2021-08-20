<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Factory\Config;

use Illuminate\Contracts\Config\Repository as ConfigContract;

class ConfigFactory
{
    /**
     * Provides application config.
     * @return ConfigContract
     */
    public static function getConfig(): ConfigContract
    {
        return PHPConfigLoader::getConfigRepositoryInstance();
    }
}
