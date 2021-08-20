<?php

declare(strict_types=1);

namespace Kalashnik\CommissionTask\Service\ExchangeRate\ExchangeRateAPI;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Kalashnik\CommissionTask\Exception\ExchangeRate\ExchangeRateAPI\NotExpectedResponseException;
use Kalashnik\CommissionTask\Service\ExchangeRate\ClientInterface;

/**
 * Client for interacting with http://exchangeratesapi.io/ and extracting exchange rates.
 */
class Client implements ClientInterface
{
    private HttpClientInterface $httpClient;
    private string $apiKey;
    private string $apiUrl;
    private string $baseCurrency;

    /**
     * Client constructor.
     * @param string $apiKey
     * @param string $baseCurrency be careful: for free plan you can specify only EUR
     */
    public function __construct(string $apiKey, string $baseCurrency)
    {
        $this->apiKey = $apiKey;
        $this->baseCurrency = $baseCurrency;
        $this->apiUrl = "http://api.exchangeratesapi.io";
        $this->setHttpClient(
            $this->createHttpClient()
        );
    }

    public function setHttpClient(HttpClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @return array format: ["success": bool, "timestamp": int, "base": str, "rates": array[string]float]
     * @throws GuzzleException
     * @throws NotExpectedResponseException
     */
    public function getExchangeRates(): array
    {
        $requestPath = '/latest';
        $response = $this->httpClient->request('GET', $requestPath);

        $responseContent = $response->getBody()->getContents();
        $data = json_decode($responseContent, true);

        $requiredResponseKeys = [
            'success',
            'timestamp',
            'base',
            'rates',
        ];

        if (!$data || !Arr::has($data, $requiredResponseKeys)) {
            throw new NotExpectedResponseException('Unexpected response',
                $requestPath, $responseContent, $response->getStatusCode()
            );
        }

        return $data;
    }

    private function createHttpClient(): HttpClientInterface
    {
        return new GuzzleClient([
            'base_uri' => $this->apiUrl,
            'query' => ['access_key' => $this->apiKey, 'base' => $this->baseCurrency],
        ]);
    }
}
