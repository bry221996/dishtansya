<?php

return [
    'auth' => [
        'login' => [
            'failed' => 'Invalid credentials',
            'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
        ],
        'register' => [
            'success' => 'User successfully registered',
            'email_already_taken' => 'Email already taken',
        ]
    ],
    'order' => [
        'create' => [
            'success' => 'You have successfully ordered this product',
            'unavailable_stock' => 'Failed to order this product due to unavailability of the stock'
        ]
    ]
];
