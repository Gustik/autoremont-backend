<?php
// access_token должен быть уникальным
return [
    'user1' => [
        'id' => 1,
        'login' => 'admin',
        'access_token' => '$2y$13$WSyE5hHsG1rWN2jV8LRHzubilrCLI5Ev/iK0r3jRuwQEs2ldRu.a2',
        'password_hash' => '$2y$13$WSyE5hHsG1rWN2jV8LRHzubilrCLI5Ev/iK0r3jRuwQEs2ldRu.a2',
        'is_admin' => '1',
        'is_active' => '1',
    ],
    'user2' => [
        'id' => 2,
        'login' => '+71234567891',
        'access_token' => '$2y$13$kkgpvJ8lnjKo8RuoR30ay.RjDf15bMcHIF7Vz1zz/6viYG5xJExU6',
        'password_hash' => '$2y$13$kkgpvJ8lnjKo8RuoR30ay.RjDf15bMcHIF7Vz1zz/6viYG5xJExU6',
        'is_admin' => '0',
        'is_active' => '1',
        'can_work' => 1,
    ],
    'user3' => [
        'id' => 3,
        'login' => '+71234567892',
        'access_token' => '$2y$13$kkgqwJ8lnjKo8RuoR30ay.RjDf15bMcHIF7Vz1zz/8viYG5xJExU9',
        'password_hash' => '$2y$13$kkgqwJ8lnjKo8RuoR30ay.RjDf15bMcHIF7Vz1zz/8viYG5xJExU9',
        'is_admin' => '0',
        'is_active' => '1',
    ],
    'cantWorkUser' => [
        'id' => 4,
        'login' => '+71234567893',
        'access_token' => '$2y$13$kkgpzJ8lnjKo8RuoR30ay.RjDf15bMcHIF7Vz1zz/6viYG5xJExU6',
        'password_hash' => '$2y$13$kkgpvJ8lnjKo8RuoR30ay.RjDf15bMcHIF7Vz1zz/6viYG5xJExU6',
        'is_admin' => '0',
        'is_active' => '1',
        'can_work' => '0',
    ],
];