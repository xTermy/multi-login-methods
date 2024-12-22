<?php

namespace StormCode\MultiLoginMethods\LoginMethods;

use Illuminate\Support\Facades\Config;
use StormCode\MultiLoginMethods\Traits\LoginMethodsTools;
use StormCode\MultiLoginMethods\LoginMethodInterface;
use StormCode\MultiLoginMethods\Models\LoginAttempt;
use Illuminate\Support\Facades\Hash;

class PasswordLogin implements LoginMethodInterface
{
    use LoginMethodsTools;

    public static function getLoginMethodId(): string
    {
        return 'password';
    }

    public static function checkAvailability(object $user): bool
    {
        self::checkUserArgumentClass($user);

        return !empty($user->password);
    }

    public static function createNewAttempt(object $user, string $ip): string
    {
        self::checkUserArgumentClass($user);

        $loginAttempt = LoginAttempt::create([
            'user_id' => $user->id,
            'tries' => 0,
            'method' => self::class,
            'code' => null,
            'ip' => $ip,
        ]);

        return $loginAttempt->toToken();
    }

    public static function checkAttempt(LoginAttempt $loginAttempt, string $passcode): bool
    {
        return Hash::check($passcode, $loginAttempt->user->password);
    }
}
