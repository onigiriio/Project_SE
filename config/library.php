<?php

return [
    /**
     * Library Management System Configuration
     */

    // Registration fee amount (in dollars)
    'registration_fee' => env('LIBRARY_REGISTRATION_FEE', 10.00),

    // Fine settings
    'fines' => [
        // Default fine amount per day for overdue books (in dollars)
        'overdue_daily_amount' => env('LIBRARY_FINE_OVERDUE_DAILY', 1.00),

        // Maximum fine amount for a single book (in dollars)
        'max_fine_amount' => env('LIBRARY_FINE_MAX_AMOUNT', 50.00),

        // Days allowed to borrow a book
        'borrow_period_days' => env('LIBRARY_BORROW_PERIOD_DAYS', 30),
    ],

    // Membership settings
    'membership' => [
        // Maximum books user can borrow simultaneously
        'max_books' => env('LIBRARY_MAX_BOOKS', 5),

        // Is membership required to borrow
        'required' => env('LIBRARY_MEMBERSHIP_REQUIRED', true),
    ],
];
