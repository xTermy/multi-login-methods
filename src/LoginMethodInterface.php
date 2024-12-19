<?php

namespace StormCode\MultiLoginMethods\LoginMethods;

use App\Models\System\User;

interface LoginMethodInterface
{
    public static function getLoginMethodId(): string;
    public static function checkAvailability(User $user): bool;
    public static function markAsChosen(User $user): bool;
}
