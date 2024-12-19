<?php

namespace StormCode\MultiLoginMethods\LoginMethods;

use App\Models\System\User;

class PasswordLogin implements LoginMethodInterface
{

    public static function getLoginMethodId(): string
    {
        return 'password';
    }

    public static function checkAvailability(User $user): bool
    {
        return !empty($user->password);
    }

    public static function markAsChosen(User $user): bool
    {
        // TODO: Implement markAsChosen() method.
    }
}
