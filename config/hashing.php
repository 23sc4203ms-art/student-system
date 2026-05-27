<?php

return [
    "driver" => env('HASH_DRIVER', 'bcrypt'),

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
    ],

    // Argon2 options. Use `HASH_DRIVER=argon2id` in your .env to select Argon2id.
    'argon' => [
        'memory' => env('ARGON_MEMORY', 1024),
        'threads' => env('ARGON_THREADS', 2),
        'time' => env('ARGON_TIME', 2),
    ],

    'argon2id' => [
        'memory' => env('ARGON2_MEMORY', 1024),
        'threads' => env('ARGON2_THREADS', 2),
        'time' => env('ARGON2_TIME', 2),
    ],
];
