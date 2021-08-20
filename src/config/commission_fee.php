<?php

return [
    /**
     * Configs for calculating commission divided into operation type and user type.
     */
    'deposit' => [
        'private_user' => [
            'percent' => '0.03',
        ],
        'business_user' => [
            'percent' => '0.03',
        ],
    ],
    'withdraw' => [
        'private_user' => [
            'percent' => '0.3',
            'free_limit_per_week' => '1000.00', // value relative to base currency (currency.base)
            'max_free_operations_per_week' => 3,
        ],
        'business_user' => [
            'percent' => '0.5',
        ]
    ]
];
