<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\ExchangeRate;

use Illuminate\Contracts\Config\Repository as ConfigContract;
use Kalashnik\CommissionTask\Entity\ExchangeRate\ExchangeRateInterface;
use Kalashnik\CommissionTask\Exception\ExchangeRate\ExchangeRateAPI\NotSpecifiedApiKey;
use Kalashnik\CommissionTask\Service\ExchangeRate\ExchangeRateAPI\Client;
use Kalashnik\CommissionTask\Service\ExchangeRate\Mapper\ExchangeRateAPIMapper;
use Kalashnik\CommissionTask\Service\ExchangeRate\Mapper\MapperInterface;

class Loader implements LoaderInterface
{
    protected ConfigContract $config;
    protected ClientInterface $client;
    protected MapperInterface $mapper;

    public function __construct(ConfigContract $config)
    {
        $this->config = $config;
        $this->client = $this->createClient();
        $this->mapper = $this->createMapper();
    }

    public function loadAndGetExchangeRate(): ExchangeRateInterface
    {
        $ratesData = $this->client->getExchangeRates();
        return $this->mapper->mapDataToExchangeRate($ratesData);
    }

    protected function createClient(): ClientInterface
    {
        $configPath = 'exchange_rate.providers.exchangeratesapi.api_key';
        $apiKey = $this->config->get($configPath);
        if (!$apiKey) {
            throw new NotSpecifiedApiKey(sprintf('You must specify API key (%s) to load ratings', $configPath));
        }
        return new Client(
            $apiKey,
            $this->config->get('currency.base')
        );
    }

    protected function createMapper(): MapperInterface
    {
        return new ExchangeRateAPIMapper();
    }
}
