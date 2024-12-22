<?php
return [
    'enabled_login_methods' => [
        StormCode\MultiLoginMethods\LoginMethods\EmailLogin::class,
        StormCode\MultiLoginMethods\LoginMethods\PasswordLogin::class,
        StormCode\MultiLoginMethods\LoginMethods\SMSLogin::class
    ],
    'auth_model' => \App\Models\User::class,
    'auth_model_table' => 'users',
    'minCodeLength' => 6,
    'maxCodeLength' => 6,
    'max_attempts' => 3,
];
