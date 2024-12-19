<?php

namespace StormCode\MultiLoginMethods\LoginMethods;

use App\Models\System\User;
use StormCode\MultiLoginMethods\Traits\LoginMethodsTools;
use StormCode\MultiLoginMethods\LoginMethodInterface;

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

    public static function markAsChosen(object $user): bool
    {
        self::checkUserArgumentClass($user);
        // TODO: Implement markAsChosen() method.
    }
}
