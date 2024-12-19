<?php

namespace StormCode\MultiLoginMethods\LoginMethods;

use App\Models\System\User;

class SMSLogin implements LoginMethodInterface
{

    public static function getLoginMethodId(): string
    {
        return 'sms';
    }

    public static function checkAvailability(User $user): bool
    {
        return !empty($user->phone);
    }

    public static function markAsChosen(User $user): bool
    {
        // TODO: Implement markAsChosen() method.
    }
}
