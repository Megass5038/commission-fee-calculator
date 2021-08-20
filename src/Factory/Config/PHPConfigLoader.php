<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Factory\Config;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as ConfigRepositoryContract;
use Symfony\Component\Finder\Finder;

/**
 * Helper class for load all *.php config files from config/
 * Implements singleton.
 */
class PHPConfigLoader
{
    protected static ?PHPConfigLoader $instance = null;
    protected Finder $finder;
    protected ConfigRepositoryContract $config;

    public function __construct()
    {
        $this->finder = new Finder();
        $this->config = $this->loadConfigRepository();
    }

    public static function getInstance(): PHPConfigLoader
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public static function getConfigRepositoryInstance(): ConfigRepositoryContract
    {
        return static::getInstance()->getConfig();
    }

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Provides ConfigRepository.
     * @return ConfigRepositoryContract
     */
    protected function loadConfigRepository(): ConfigRepositoryContract
    {
        $configPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
        $configs = [];

        // load all php config files at {filename} => {config} format from config/ directory
        foreach ($this->finder->files()->in($configPath)->name('*.php') as $file) {
            $filePath = $file->getRealPath();
            $fileName = pathinfo($filePath, PATHINFO_FILENAME);
            $configs[$fileName] = require_once $filePath;
        }

        return new Repository($configs);
    }
}
