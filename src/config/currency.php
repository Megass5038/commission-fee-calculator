<?php

return [
    /**
     * Base currency of application.
     * When calculating values from different currencies, the base is used, as an auxiliary.
     */
    'base' => 'EUR', //
    'format' => [
        /**
         * Configs for formatting the total amount.
         * If need to override the configs for some currency, you can add it to the corresponding array (see JPY)
         */
        'default' => [
            'decimal_separator' => '.',
            'thousands_separator' => '',
            'decimals' => 2,
        ],
        'currencies' => [
            'JPY' => [
                'decimals' => 0,
            ],
        ],
    ],
];
