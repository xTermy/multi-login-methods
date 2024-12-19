<?php

namespace StormCode\MultiLoginMethods\LoginMethods;

use StormCode\MultiLoginMethods\Traits\LoginMethodsTools;
use StormCode\MultiLoginMethods\LoginMethodInterface;

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

    public static function markAsChosen(object $user): bool
    {
        self::checkUserArgumentClass($user);
        // TODO: Implement markAsChosen() method.
    }
}
