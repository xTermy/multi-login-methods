<?php

namespace StormCode\MultiLoginMethods\LoginMethods;

use App\Models\System\User;

class EmailLogin implements LoginMethodInterface
{

    public static function getLoginMethodId(): string
    {
        return 'email';
    }

    public static function checkAvailability(User $user): bool
    {
        return true;
    }

    public static function markAsChosen(User $user): bool
    {
        // TODO: Implement markAsChosen() method.
    }
}
