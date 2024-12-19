<?php
return [
    'enabled_login_methods' => [
        \App\Builders\LoginMethods\EmailLogin::class,
        \App\Builders\LoginMethods\PasswordLogin::class,
        \App\Builders\LoginMethods\SMSLogin::class
    ]
];