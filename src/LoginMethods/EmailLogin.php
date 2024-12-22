<?php

namespace StormCode\MultiLoginMethods\LoginMethods;

use StormCode\MultiLoginMethods\Traits\LoginMethodsTools;
use StormCode\MultiLoginMethods\LoginMethodInterface;
use StormCode\MultiLoginMethods\Models\LoginAttempt;
use Illuminate\Support\Facades\Config;

class EmailLogin implements LoginMethodInterface
{
    use LoginMethodsTools;

    public static function getLoginMethodId(): string
    {
        return 'email';
    }

    public static function checkAvailability(object $user): bool
    {
        self::checkUserArgumentClass($user);

        return true;
    }

    public static function createNewAttempt(object $user, string $ip): string
    {
        self::checkUserArgumentClass($user);

        $loginAttempt = LoginAttempt::create([
            'user_id' => $user->id,
            'tries' => 0,
            'method' => self::class,
            'code' => self::generateRandomCode(Config::integer('multi_login_methods.minCodeLength', 6), Config::integer('multi_login_methods.maxCodeLength', 6)),
            'ip' => $ip,
        ]);

        return $loginAttempt->toToken();
    }

    public static function checkAttempt(LoginAttempt $loginAttempt, string $passcode): bool
    {
        if ($loginAttempt->code === $passcode) {
            return true;
        }

        return false;
    }
}
