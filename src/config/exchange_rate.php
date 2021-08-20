<?php

return [
    /**
     * Provider for exchange rate that will use application.
     */
    'provider' => getenv('EXCHANGE_RATE_PROVIDER') ? getenv('EXCHANGE_RATE_PROVIDER') : 'stub',
    'providers' => [
        'exchangeratesapi' => [
            'api_key' => getenv('EXCHANGERATESAPI_KEY'),
        ],
        /**
         * Provider just directly provides the rates specified in the array.
         */
        'stub' => [
            'rates' => [
                'USD' => '1.1497',
                'JPY' => '129.53',
            ],
        ],
    ],
];
