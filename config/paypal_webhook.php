<?php

return [
    'paypal_client_id' => env('PAYPAL_CLIENT_ID'),
    'paypal_client_secret' => env('PAYPAL_CLIENT_SECRET'),
    'paypal_plan_id' => env('PAYPAL_PLAN_ID'),
    'paypal_environment' => env('PAYPAL_ENVIRONMENT', 'sandbox'),
];
