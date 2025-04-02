# MultiMethod Login
## Installation
Install through Composer:
```bash
composer require stormcode/multi-login-methods
```
Add trait to your User model:
```php
use StormCode\MultiLoginMethods\Traits\MultipleLoginMethods
```

Publish config, migration and view:
```bash
php artisan vendor:publish --provider=StormCode\MultiLoginMethods\MultiLoginMethodsServiceProvider
```

Migrate database:
```bash
php artisan migrate
```

## Configuration
Whole configuration can be done trough the config file ```config/multiLoginMethods.php```:
```php
<?php
return [
    /**
     * Allowed login methods
     */
    'enabled_login_methods' => [
        StormCode\MultiLoginMethods\LoginMethods\EmailLogin::class,
        StormCode\MultiLoginMethods\LoginMethods\PasswordLogin::class,
        //StormCode\MultiLoginMethods\LoginMethods\SMSLogin::class
    ],

    /**
     * Drivers used to send emails, phones or other
     */
    'send_drivers' => [
        StormCode\MultiLoginMethods\LoginMethods\EmailLogin::class => \StormCode\MultiLoginMethods\SendDrivers\EmailDriver::class,
        //StormCode\MultiLoginMethods\LoginMethods\SMSLogin::class
    ],

    /**
     * User model settings
     */
    'auth_model' => \App\Models\User::class,
    'auth_model_table' => 'users',

    /**
     * Max and minimum number of numbers in code sent to email or phone
     */
    'minCodeLength' => 6,
    'maxCodeLength' => 6,

    /**
     * Allowed attempts count, after which login attempt will be blocked
     */
    'max_attempts' => 3,
];
```

