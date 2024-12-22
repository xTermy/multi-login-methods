<?php

namespace StormCode\MultiLoginMethods\LoginMethods;

use http\Exception\RuntimeException;
use Illuminate\Support\Facades\Config;
use StormCode\MultiLoginMethods\Traits\LoginMethodsTools;
use StormCode\MultiLoginMethods\LoginMethodInterface;
use StormCode\MultiLoginMethods\Models\LoginAttempt;

class SMSLogin implements LoginMethodInterface
{
    use LoginMethodsTools;

    public static function getLoginMethodId(): string
    {
        return 'sms';
    }

    public static function checkAvailability(object $user): bool
    {
        self::checkUserArgumentClass($user);
        return !empty($user->phone);
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

        $sendDriver = Config::string('multiLoginMethods.send_drivers.'.self::class);
        if (isset($sendDriver)) {
            $sendDriver::send($user->phone, $loginAttempt->code);
        } else {
            throw new RuntimeException('SMS login method send driver not found.');
        }

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
