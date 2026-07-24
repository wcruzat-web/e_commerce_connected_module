<?php

return [
    'finance' => [
        'api_key' => env('FINANCE_API_KEY'),
        'webhook_url' => env('FINANCE_WEBHOOK_URL'),
    ],
    'sales' => [
        'api_key' => env('SALES_API_KEY'),
        'webhook_url' => env('SALES_WEBHOOK_URL'),
    ],
];
