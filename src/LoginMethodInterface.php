<?php

namespace StormCode\MultiLoginMethods;

use App\Models\System\User;

interface LoginMethodInterface
{
    public static function getLoginMethodId(): string;
    public static function checkAvailability(object $user): bool;
    public static function markAsChosen(object $user): bool;
}
