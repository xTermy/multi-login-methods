<?php

namespace StormCode\MultiLoginMethods\LoginMethods;

use App\Models\System\User;
use StormCode\MultiLoginMethods\Traits\LoginMethodsTools;
use StormCode\MultiLoginMethods\LoginMethodInterface;

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

    public static function markAsChosen(object $user): bool
    {
        self::checkUserArgumentClass($user);
        // TODO: Implement markAsChosen() method.
    }
}
