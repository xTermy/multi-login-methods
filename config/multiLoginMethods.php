<?php
return [
    'enabled_login_methods' => [
        StormCode\MultiLoginMethods\LoginMethods\EmailLogin::class,
        StormCode\MultiLoginMethods\LoginMethods\PasswordLogin::class,
        StormCode\MultiLoginMethods\LoginMethods\SMSLogin::class
    ],
    'auth_model' => \App\Models\User::class
];
